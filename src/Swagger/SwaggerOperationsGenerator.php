<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodParameterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Operation\OperationsMethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerOperationsHelperInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\OperationsMethodGeneratorFactoryInterface;

class SwaggerOperationsGenerator implements SwaggerOperationsGeneratorInterface
{
    /** @var ClassGeneratorFactoryInterface */
    protected $classGeneratorFactory;
    /** @var OperationsMethodGeneratorFactoryInterface */
    protected $methodFactory;
    /** @var SwaggerOperationsHelperInterface */
    protected $helper;

    /** @var string */
    protected $folder = '';
    /** @var string */
    protected $namespace = '';
    /** @var string */
    protected $modelNamespace = '';
    /** @var string */
    protected $indent = '    ';
    /** @var array */
    protected $paths = [];
    /** @var ClassGenerator[] */
    protected $classGenerators = [];
    /** @var string[] */
    protected $throwsClasses = [];
    /** @var string|null */
    protected $abstractOperationClass;
    /** @var bool */
    protected $overwrite = false;

    /**
     * @param ClassGeneratorFactoryInterface $classGeneratorFactory
     * @param OperationsMethodGeneratorFactoryInterface $methodFactory
     * @param SwaggerOperationsHelperInterface $helper
     */
    public function __construct(
        ClassGeneratorFactoryInterface $classGeneratorFactory,
        OperationsMethodGeneratorFactoryInterface $methodFactory,
        SwaggerOperationsHelperInterface $helper
    )
    {
        $this->classGeneratorFactory = $classGeneratorFactory;
        $this->methodFactory = $methodFactory;
        $this->helper = $helper;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(string $folder, string $namespace, string $modelNamespace, string $indent = '    ')
    {
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->modelNamespace = $modelNamespace;
        $this->indent = $indent;

        $this->paths = [];
        $this->classGenerators = [];
        $this->throwsClasses = [];
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function generate(): bool
    {
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function processPaths(array $json): bool
    {
        if (!isset($json['paths'])) {
            return false;
        }
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function generateClass(string $path, array $operationConfigurations): ?ClassGeneratorInterface
    {
        $filePath = $this->getFilePathFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );
        if (!$this->overwrite && is_file($filePath)) {
            return null;
        }

        // Class
        $classGenerator = $this->getOrCreateClassGenerator($path, $filePath);

        // Methods
        $this->processOperations($classGenerator, $path, $operationConfigurations);

        // File creation
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (false === file_put_contents($filePath, $classGenerator->generate($this->indent))) {
            throw new Exception(sprintf('Unable to generate the class : "%s" !', $filePath));
        }

        return $classGenerator;
    }

    /**
     * {@inheritDoc}
     */
    protected function getOrCreateClassGenerator(string $path, string $filePath): ClassGeneratorInterface
    {
        [$namespace, $className] = $this->getClassNameAndNamespaceFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );

        if ($this->hasClassGenerator($filePath)) {
            return $this->classGenerators[$filePath];
        }

        $classGenerator = $this->classGeneratorFactory->createClassGenerator();
        $classGenerator->configure($namespace, $className);
        if ($this->abstractOperationClass !== null) {
            $classGenerator->setExtendClass($this->abstractOperationClass);
        }

        $this->classGenerators[$filePath] = $classGenerator;

        return $classGenerator;
    }

    /**
     * {@inheritDoc}
     */
    protected function hasClassGenerator(string $filePath): bool
    {
        return isset($this->classGenerators[$filePath]);
    }

    /**
     * {@inheritDoc}
     */
    public function processOperations(
        ClassGeneratorInterface $classGenerator,
        string $path,
        array $operationConfigurations
    ): void
    {
        foreach ($operationConfigurations as $operation => $operationConfiguration) {
            if (!is_array($operationConfiguration)) {
                continue;
            }
            $this->processOperation($classGenerator, $path, $operation, $operationConfiguration);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function processOperation(
        ClassGeneratorInterface $classGenerator,
        string $path,
        string $operation,
        array $operationConfiguration
    ): void {

        $operation = strtolower($operation);
        if (!in_array($operation, ['get', 'post', 'put', 'patch', 'delete'])) {
            return;
        }

        $operationMethodGenerator = $this->methodFactory->createOperationMethodGenerator(
            $classGenerator->getUsesGenerator()
        );

        $operationMethodName = $this->helper::getOperationMethodName($path, $operation, $operationConfiguration);
        $operationMethodGenerator->setName($operationMethodName);

        $returnType = $this->processOperationReturnType($operationConfiguration);
        $operationMethodGenerator->addReturnType($returnType);

        if (isset($operationConfiguration['description'])) {
            $operationMethodGenerator->getPhpDocGenerator()->addDescriptionLine($operationConfiguration['description']);
        }
        $operationMethodGenerator->getPhpDocGenerator()->addDescriptionLine(
            sprintf('path: %s', $path)
        );
        $operationMethodGenerator->getPhpDocGenerator()->addDescriptionLine(
            sprintf('method: %s', strtoupper($operation))
        );

        $operationParameters = [];
        if (isset($operationConfiguration['parameters'])) {
            $operationParameters = $operationConfiguration['parameters'];
            $this->processOperationParameters($classGenerator, $operationMethodGenerator, $operationParameters);
        }

        $this->processOperationThrowsClass($classGenerator, $operationMethodGenerator);

        $operationMethodGenerator->addMethodBodyFromSwaggerConfiguration(
            $path,
            $operation,
            $operationParameters,
            $this->indent
        );

        $classGenerator->getMethodsGenerator()->addMethod($operationMethodGenerator);
    }

    /**
     * @param ClassGeneratorInterface $classGenerator
     * @param OperationsMethodGeneratorInterface $operationMethodGenerator
     */
    public function processOperationThrowsClass(
        ClassGeneratorInterface $classGenerator,
        OperationsMethodGeneratorInterface $operationMethodGenerator
    ): void
    {
        foreach ($this->throwsClasses as $throwsClass => $className) {
            $classGenerator->getUsesGenerator()->guessUse($throwsClass);
            $operationMethodGenerator->getPhpDocGenerator()->addThrowsLine($className);
        }
    }

    /**
     * @param array $operationConfiguration
     *
     * @return string
     */
    public function processOperationReturnType(array $operationConfiguration): string
    {
        $returnType = null;
        if (isset($operationConfiguration['responses'])) {
            $returnType = $this->helper::getReturnType($operationConfiguration['responses']);
        }

        if ($returnType !== null) {
            $returnType = $this->getPhpNameFromType($returnType);
        } else {
            $returnType = 'void';
        }

        return $returnType;
    }

    /**
     * {@inheritDoc}
     */
    public function processOperationParameters(ClassGeneratorInterface $classGenerator, MethodGeneratorInterface $methodGenerator, array $operationParameters): void
    {
        foreach ($operationParameters as $parameterConfiguration) {
            $methodParameterGenerator = $this->createAnOperationParameter($classGenerator, $parameterConfiguration);
            if ($methodParameterGenerator === null) {
                continue;
            }
            $methodGenerator->addParameter($methodParameterGenerator);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createAnOperationParameter(ClassGeneratorInterface $classGenerator, array $parameterConfiguration): ?MethodParameterGeneratorInterface
    {
        if (!isset($parameterConfiguration['name'])) {
            return null;
        }

        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($parameterConfiguration);
        if ($type !== null) {
            $type = $this->getPhpNameFromType($type);
        }
        $types = [$type];

        $name = lcfirst($this->helper::cleanStr($parameterConfiguration['name']));

        $value = $this->buildValueForOperationParameter($parameterConfiguration, $type);

        $description = '';
        if (isset($parameterConfiguration['description'])) {
            $description = $parameterConfiguration['description'];
        }

        $methodParameterGenerator = $this->methodFactory->createMethodParameterGenerator(
            $classGenerator->getUsesGenerator()
        );
        $methodParameterGenerator->configure(
            $types,
            $name,
            $value,
            false,
            $description
        );

        return $methodParameterGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function buildValueForOperationParameter(array $parameterConfiguration, ?string $type): ?string
    {
        $value = null;
        if (isset($parameterConfiguration['default'])) {
            $value = (string) $parameterConfiguration['default'];
            if ($type === 'string') {
                $value = "'" . addslashes($value) . "'";
            }
        }

        if (isset($parameterConfiguration['required'])) {
            if ((bool)$parameterConfiguration['required']) {
                $value = null;
            } else {
                $value = $value ?? 'null';
            }
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassNameAndNamespaceFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): array
    {
        $classPath = $this->helper::getClassPathFromPath($path);
        $classParts = explode('/', $classPath);
        $className = array_pop($classParts);
        $namespace = implode('\\', $classParts);
        $namespace = ($namespace === '' ? '' : '\\'.$namespace);
        $namespace = $this->namespace . $namespace;

        return [
            $namespace,
            $classPrefix . $className . $classSuffix,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpNameFromType(string $type): string
    {
        $class = $type;
        if (preg_match('$^#/definitions/$', $type)) {
            $className = preg_replace('$#/definitions/$', '', $type);
            $className = $this->helper::camelize($className);
            $class = '\\' . $this->modelNamespace . '\\' . $className;
        }

        if (preg_match('#\[]$#', $type) && $class !== $type) {
            $class .= "[]";
        }

        return $class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilePathFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): string
    {
        $filePath = sprintf('%s/%s%s%s.php', $this->folder, $classPrefix, $this->helper::getClassPathFromPath($path), $classSuffix);

        return preg_replace('#/' . $classPrefix . $classSuffix . '\.php$#', $classPrefix . $classSuffix . '.php', $filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * {@inheritDoc}
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * {@inheritDoc}
     */
    public function getHelper(): SwaggerOperationsHelperInterface
    {
        return $this->helper;
    }

    /**
     * {@inheritDoc}
     */
    public function setHelper(SwaggerOperationsHelperInterface $helper): void
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritDoc}
     */
    public function getThrowsClasses(): array
    {
        return $this->throwsClasses;
    }

    /**
     * {@inheritDoc}
     */
    public function setThrowsClasses(array $throwsClasses): void
    {
        $this->throwsClasses = $throwsClasses;
    }

    /**
     * {@inheritDoc}
     */
    public function getAbstractOperationClass(): ?string
    {
        return $this->abstractOperationClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setAbstractOperationClass(?string $abstractOperationClass): void
    {
        $this->abstractOperationClass = $abstractOperationClass;
    }

    /**
     * {@inheritDoc}
     */
    public function isOverwrite(): bool
    {
        return $this->overwrite;
    }

    /**
     * {@inheritDoc}
     */
    public function setOverwrite(bool $overwrite): void
    {
        $this->overwrite = $overwrite;
    }
}
