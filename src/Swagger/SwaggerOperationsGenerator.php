<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Exception;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelperInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\OperationsMethodFactoryInterface;

class SwaggerOperationsGenerator implements SwaggerOperationsGeneratorInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;
    /** @var OperationsMethodFactoryInterface */
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
    /** @var ClassBuilder[] */
    protected $classBuilders = [];
    /** @var string[] */
    protected $throwsClasses = [];
    /** @var string|null */
    protected $abstractOperationClass;
    /** @var bool */
    protected $overwrite = false;

    /**
     * @param ClassFactoryInterface $classFactory
     * @param OperationsMethodFactoryInterface $methodFactory
     * @param SwaggerOperationsHelperInterface $helper
     */
    public function __construct(
        ClassFactoryInterface $classFactory,
        OperationsMethodFactoryInterface $methodFactory,
        SwaggerOperationsHelperInterface $helper
    )
    {
        $this->classFactory = $classFactory;
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
        $this->classBuilders = [];
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
    public function generateClass(string $path, array $operationConfigurations)
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
        $classBuilder = $this->getOrCreateClassBuilder($path, $filePath);

        // Methods
        foreach ($operationConfigurations as $operation => $operationConfiguration) {
            if (!is_array($operationConfiguration)) {
                continue;
            }
            $this->processOperation($classBuilder, $path, $operation, $operationConfiguration);
        }

        // File creation
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (false === file_put_contents($filePath, $classBuilder->build($this->indent))) {
            throw new Exception(sprintf('Unable to generate the class : "%s" !', $filePath));
        }

        return $classBuilder;
    }

    /**
     * {@inheritDoc}
     */
    protected function getOrCreateClassBuilder(string $path, string $filePath): ClassBuilderInterface
    {
        [$namespace, $className] = $this->getClassNameAndNamespaceFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );

        if ($this->hasClassBuilder($filePath)) {
            return $this->classBuilders[$filePath];
        }

        $classBuilder = $this->classFactory->createClassBuilder();
        $classBuilder->configure($namespace, $className);
        if ($this->abstractOperationClass !== null) {
            $useBuilder = $classBuilder->getUsesBuilder();
            $useBuilder->addUse($this->abstractOperationClass);
            $extendClassName = $useBuilder->getInternalUseName($this->abstractOperationClass);
            $classBuilder->setExtendClassName($extendClassName);
        }

        $this->classBuilders[$filePath] = $classBuilder;

        return $classBuilder;
    }

    /**
     * {@inheritDoc}
     */
    protected function hasClassBuilder(string $filePath): bool
    {
        return isset($this->classBuilders[$filePath]);
    }

    /**
     * {@inheritDoc}
     */
    public function processOperation(
        ClassBuilderInterface $classBuilder,
        string $path,
        string $operation,
        array $operationConfiguration
    ): void {

        $operation = strtolower($operation);
        if (!in_array($operation, ['get', 'post', 'put', 'delete'])) {
            return;
        }

        $returnType = null;
        if (isset($operationConfiguration['responses'])) {
            $returnType = $this->helper::getReturnType($operationConfiguration['responses']);
        }
        if ($returnType !== null) {
            $returnType = $this->getPhpNameFromType($returnType);
        } else {
            $returnType = 'void';
        }

        $operationMethodBuilder = $this->methodFactory->createOperationMethodBuilder(
            $classBuilder->getUsesBuilder()
        );
        $operationMethodName = $this->helper::getOperationMethodName($path, $operation, $operationConfiguration);
        $operationMethodBuilder->setName($operationMethodName);
        $operationMethodBuilder->addReturnType($returnType);

        if (isset($operationConfiguration['description'])) {
            $operationMethodBuilder->getPhpDocBuilder()->addDescriptionLine($operationConfiguration['description']);
        }
        $operationMethodBuilder->getPhpDocBuilder()->addDescriptionLine(
            sprintf('path: %s', $path)
        );
        $operationMethodBuilder->getPhpDocBuilder()->addDescriptionLine(
            sprintf('method: %s', strtoupper($operation))
        );

        $operationParameters = [];
        if (isset($operationConfiguration['parameters'])) {
            $operationParameters = $operationConfiguration['parameters'];
            $this->processOperationParameters($classBuilder, $operationMethodBuilder, $operationParameters);
        }

        foreach ($this->throwsClasses as $throwsClass => $className) {
            $classBuilder->getUsesBuilder()->addUse($throwsClass);
            $operationMethodBuilder->getPhpDocBuilder()->addThrowsLine($className);
        }

        $operationMethodBuilder->addMethodBodyFromSwaggerConfiguration(
            $path,
            $operation,
            $operationParameters,
            $this->indent
        );

        $classBuilder->getMethodsBuilder()->addMethod($operationMethodBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function processOperationParameters(ClassBuilderInterface $classBuilder, MethodBuilderInterface $methodBuilder, array $operationParameters): void
    {
        foreach ($operationParameters as $parameterConfiguration) {
            $methodParameterBuilder = $this->createAnOperationParameter($classBuilder, $parameterConfiguration);
            if ($methodParameterBuilder === null) {
                continue;
            }
            $methodBuilder->addParameter($methodParameterBuilder);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function createAnOperationParameter(ClassBuilderInterface $classBuilder, array $parameterConfiguration): ?MethodParameterBuilderInterface
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
        $value = null;
        $description = '';

        if (isset($parameterConfiguration['default'])) {
            $value = (string) $parameterConfiguration['default'];
            if ($type === 'string') {
                $value = "'" . addslashes($value) . "'";
            }
        }

        if (isset($parameterConfiguration['description'])) {
            $description = $parameterConfiguration['description'];
        }

        if (isset($parameterConfiguration['required'])) {
            if ((bool) $parameterConfiguration['required']) {
                $value = null;
                $description .= ' (required)';
            } else {
                $value = $value ?? 'null';
                $description .= ' (optional)';
            }
        }

        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $classBuilder->getUsesBuilder()
        );
        $methodParameterBuilder->configure(
            $types,
            $name,
            $value,
            false,
            $description
        );

        return $methodParameterBuilder;
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
        $namespace = $this->namespace . ($namespace === '' ? '' : $namespace);

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
        if (preg_match('$^#/definitions/$', $type)) {
            $className = preg_replace('$#/definitions/$', '', $type);
            $className = $this->helper::camelize($className);
            return '\\' . $this->modelNamespace . '\\' . $className;
        }

        return $type;
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
