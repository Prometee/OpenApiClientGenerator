<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\ClassBuilder;
use Prometee\SwaggerClientBuilder\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\Method\MethodParameterBuilder;
use Prometee\SwaggerClientBuilder\Swagger\Builder\OperationMethodBuilder;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelper;

class SwaggerOperationsGenerator
{
    const CLASS_SUFFIX = 'Operations';

    /** @var string */
    protected $folder;
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $modelNamespace;
    /** @var string */
    protected $indent;

    /** @var array */
    protected $paths;

    /** @var callable|SwaggerOperationsHelper */
    protected $helper;

    /** @var ClassBuilder[] */
    protected $classBuilders;

    /** @var string[] */
    protected $throwsClasses;

    /** @var string */
    protected $abstractOperationClass;

    /**
     * @param string $folder
     * @param string $namespace
     * @param string $modelNamespace
     * @param string $indent
     */
    public function __construct(string $folder, string $namespace, string $modelNamespace, string $indent = '    ')
    {
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->modelNamespace = $modelNamespace;
        $this->indent = $indent;
        $this->paths = [];
        $this->classBuilders = [];
        $this->throwsClasses = [];
        $this->helper = SwaggerOperationsHelper::class;
    }

    /**
     * @param bool $overwrite
     *
     * @return bool
     */
    public function generate(bool $overwrite = false): bool
    {
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations, $overwrite);
        }

        return true;
    }

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
     * @param string $path
     * @param array $operationConfigurations
     * @param bool $overwrite
     *
     * @return bool|int
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
     * @param string $filePath
     * @param string $namespace
     * @param string $className
     *
     * @return ClassBuilder
     */
    protected function getOrCreateClassBuilder(string $filePath, string $namespace, string $className): ClassBuilder
    {
        if ($this->hasClassBuilder($filePath)) {
            return $this->classBuilders[$filePath];
        }

        $classBuilder = new ClassBuilder($namespace, $className);

        if ($this->abstractOperationClass !== null) {
            $useBuilder = $classBuilder->getUsesBuilder();
            $useBuilder->addUse($this->abstractOperationClass);
            $extendClassName = $useBuilder->getInternalClassName($this->abstractOperationClass);
            $classBuilder->setExtendClassName($extendClassName);
        }

        $this->classBuilders[$filePath] = $classBuilder;

        return $classBuilder;
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    protected function hasClassBuilder(string $filePath): bool
    {
        return isset($this->classBuilders[$filePath]);
    }

    public function processOperation(
        ClassBuilder $classBuilder,
        string $path,
        string $operation,
        array $operationConfiguration
    ) {
        $returnType = null;
        if (isset($operationConfiguration['responses'])) {
            $returnType = $this->helper::getReturnType($operationConfiguration['responses']);
        }
        if ($returnType !== null) {
            $returnType = $this->minifyClassToUses($classBuilder, $returnType);
        }

        $operationMethodBuilder = new OperationMethodBuilder(
            $this->helper::getOperationMethodName($path, $operation, $operationConfiguration),
            $returnType
        );

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

    public function processOperationParameters(ClassBuilder $classBuilder, MethodBuilderInterface $methodBuilder, array $operationParameters)
    {
        foreach ($operationParameters as $parameterConfiguration) {
            $methodParameterBuilder = $this->createAnOperationParameter($classBuilder, $parameterConfiguration);
            if ($methodParameterBuilder === null) {
                continue;
            }
            $methodBuilder->addParameter($methodParameterBuilder);
        }
    }

    public function createAnOperationParameter(ClassBuilder $classBuilder, array $parameterConfiguration): ?MethodParameterBuilder
    {
        if (!isset($parameterConfiguration['name'])) {
            return null;
        }
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($parameterConfiguration);
        if ($type !== null) {
            $type = $this->minifyClassToUses($classBuilder, $type);
        }
        $name = lcfirst($this->helper::cleanPropertyName($parameterConfiguration['name']));
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

        return new MethodParameterBuilder(
            $type,
            $name,
            $value,
            false,
            $description
        );
    }

    /**
     * @param string $path
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return array
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
     * @param ClassBuilder $classBuilder
     * @param string $type
     *
     * @return string
     */
    public function minifyClassToUses(ClassBuilder $classBuilder, string $type): string
    {
        if (preg_match('$^#/definitions/$', $type)) {
            $type = preg_replace('$#/definitions/$', '', $type);
            $type = '\\' . $this->modelNamespace . '\\' . ucfirst($type);
        }

        if (preg_match('#^\\\\#', $type)) {
            $classBuilder->getUsesBuilder()->addUse(trim($type, '\\[]'));
            $types = explode('\\', trim($type, '\\'));
            $type = end($types);
        }

        return $type;
    }

    /**
     * @param string $path
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return string
     */
    protected function getFilePathFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): string
    {
        $filePath = sprintf('%s/%s%s%s.php', $this->folder, $classPrefix, $this->helper::getClassPathFromPath($path), $classSuffix);

        return preg_replace('#/' . $classPrefix . $classSuffix . '\.php$#', $classPrefix . $classSuffix . '.php', $filePath);
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * @return string
     */
    public function getHelper(): string
    {
        return $this->helper;
    }

    /**
     * @param string $helper
     */
    public function setHelper(string $helper): void
    {
        $this->helper = $helper;
    }

    /**
     * @return string[]
     */
    public function getThrowsClasses(): array
    {
        return $this->throwsClasses;
    }

    /**
     * @param string[] $throwsClasses
     */
    public function setThrowsClasses(array $throwsClasses): void
    {
        $this->throwsClasses = $throwsClasses;
    }

    /**
     * @return string
     */
    public function getAbstractOperationClass(): string
    {
        return $this->abstractOperationClass;
    }

    /**
     * @param string $abstractOperationClass
     */
    public function setAbstractOperationClass(string $abstractOperationClass): void
    {
        $this->abstractOperationClass = $abstractOperationClass;
    }
}
