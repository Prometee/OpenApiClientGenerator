<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\Factory\MethodFactoryInterface as SwaggerMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelperInterface;

class SwaggerOperationsGenerator implements SwaggerOperationsGeneratorInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;
    /** @var MethodFactoryInterface */
    protected $methodFactory;
    /** @var SwaggerMethodFactoryInterface */
    protected $swaggerMethodFactory;

    /** @var string */
    protected $folder;
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $modelNamespace;
    /** @var string */
    protected $indent;

    /** @var array */
    protected $paths = [];

    /** @var SwaggerOperationsHelperInterface */
    protected $helper;

    /** @var ClassBuilder[] */
    protected $classBuilders = [];

    /** @var string[] */
    protected $throwsClasses = [];

    /** @var string */
    protected $abstractOperationClass;

    /**
     * @param ClassFactoryInterface $classFactory
     * @param MethodFactoryInterface $methodFactory
     * @param SwaggerMethodFactoryInterface $swaggerMethodFactory
     * @param SwaggerOperationsHelperInterface $helper
     */
    public function __construct(
        ClassFactoryInterface $classFactory,
        MethodFactoryInterface $methodFactory,
        SwaggerMethodFactoryInterface $swaggerMethodFactory,
        SwaggerOperationsHelperInterface $helper
    )
    {
        $this->classFactory = $classFactory;
        $this->methodFactory = $methodFactory;
        $this->swaggerMethodFactory = $swaggerMethodFactory;
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
     */
    public function generate(bool $overwrite = false): bool
    {
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations, $overwrite);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function processPaths(array $json, bool $overwrite = false): bool
    {
        if (!isset($json['paths'])) {
            return false;
        }
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations, $overwrite);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function generateClass(string $path, array $operationConfigurations, bool $overwrite = false)
    {
        $filePath = $this->getFilePathFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );

        [$namespace, $className] = $this->getClassNameAndNamespaceFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );

        $classBuilder = $this->getOrCreateClassBuilder($filePath, $namespace, $className);

        foreach ($operationConfigurations as $operation => $operationConfiguration) {
            $operation = strtolower($operation);
            if (!in_array($operation, ['get', 'post', 'put', 'delete'])) {
                continue;
            }
            $this->processOperation($classBuilder, $path, $operation, $operationConfiguration);
        }

        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return file_put_contents($filePath, $classBuilder->build($this->indent));
    }

    /**
     * {@inheritDoc}
     */
    protected function getOrCreateClassBuilder(string $filePath, string $namespace, string $className): ClassBuilderInterface
    {
        if ($this->hasClassBuilder($filePath)) {
            return $this->classBuilders[$filePath];
        }

        $classBuilder = $this->classFactory->createClassBuilder();
        $classBuilder->configure($namespace, $className);
        if ($this->abstractOperationClass !== null) {
            $useBuilder = $classBuilder->getUsesBuilder();
            $useBuilder->addUse($this->abstractOperationClass);
            $extendClassName = $useBuilder->getInternalUseClassName($this->abstractOperationClass);
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
        $returnType = null;
        if (isset($operationConfiguration['responses'])) {
            $returnType = $this->helper::getReturnType($operationConfiguration['responses']);
        }
        if ($returnType !== null) {
            $returnType = $this->getPhpNameFromType($returnType);
        } else {
            $returnType = 'void';
        }

        $operationMethodBuilder = $this->swaggerMethodFactory->createOperationMethodBuilder(
            $classBuilder->getUsesBuilder()
        );
        $operationMethodName = $this->helper::getOperationMethodName($path, $operation, $operationConfiguration);
        $operationMethodBuilder->setName($operationMethodName);
        $operationMethodBuilder->setReturnType($returnType);

        if (isset($operationConfiguration['description'])) {
            foreach (explode("\n", $operationConfiguration['description']) as $line) {
                $operationMethodBuilder->getPhpDocBuilder()->addDescriptionLine($line);
            }
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
            if ($parameterConfiguration['required']) {
                $value = null;
                $description .= ' (required)';
            } else {
                $type = '?' . $type;
                $value = $value !== null ? $value : 'null';
                $description .= ' (optional)';
            }
        }

        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $classBuilder->getUsesBuilder()
        );
        $methodParameterBuilder->configure(
            (array) $type,
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
        $className = $this->helper::getClassPathFromPath($path);
        $namespace = $this->namespace . '\\' . preg_replace('#/#', '\\', $className);
        $className = basename($className);
        $namespace = preg_replace(
            '#\\\\' . $className . '$#',
            '',
            $namespace
        );

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
    public function getAbstractOperationClass(): string
    {
        return $this->abstractOperationClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setAbstractOperationClass(string $abstractOperationClass): void
    {
        $this->abstractOperationClass = $abstractOperationClass;
    }
}
