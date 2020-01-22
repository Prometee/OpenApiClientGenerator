<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelperInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other\ModelPropertiesBuilderInterface;

class SwaggerModelGenerator implements SwaggerModelGeneratorInterface
{
    /** @var ModelClassFactoryInterface */
    protected $classFactory;
    /** @var ModelMethodFactoryInterface */
    protected $methodFactory;
    /** @var SwaggerModelHelperInterface */
    protected $helper;

    /** @var string */
    protected $folder;
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $indent;
    /** @var array */
    protected $definitions = [];

    /**
     * @param ModelClassFactoryInterface $classFactory
     * @param ModelMethodFactoryInterface $methodFactory
     * @param SwaggerModelHelperInterface $helper
     */
    public function __construct(
        ModelClassFactoryInterface $classFactory,
        ModelMethodFactoryInterface $methodFactory,
        SwaggerModelHelperInterface $helper
    )
    {
        $this->classFactory = $classFactory;
        $this->methodFactory = $methodFactory;
        $this->helper = $helper;
    }

    /**
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function configure(string $folder, string $namespace, string $indent = '    '): void
    {
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->indent = $indent;
        $this->definitions = [];
    }

    /**
     * {@inheritDoc}
     */
    public function generate(bool $overwrite = false): bool
    {
        foreach ($this->definitions as $definitionName => $definition) {
            if (!isset($definition['type'])) {
                return false;
            }
            if ($definition['type'] !== 'object') {
                continue;
            }
            $this->generateClass($definitionName, $definition, $overwrite);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function generateClass(string $definitionName, array $definition, bool $overwrite = false)
    {
        $filePath = $this->getFilePathFromDefinitionName($definitionName);
        if (!$overwrite && is_file($filePath)) {
            return false;
        }

        // Class
        if (isset($definition['properties'])) {
            $properties = $definition['properties'];
            $extendClass = null;
        } else {
            $properties = $definition['allOf'][1]['properties'];
            $extendClass = $this->getPhpTypeFromPropertyConfig($definition['allOf'][0]);
        }
        $classBuilder = $this->classFactory->createClassBuilder();
        [$namespace, $className] = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);
        $classBuilder->configure($namespace, $className, $extendClass);

        // Properties
        /** @var ModelPropertiesBuilderInterface $modelPropertiesBuilder */
        $modelPropertiesBuilder = $classBuilder->getPropertiesBuilder();
        foreach ($properties as $propertyName => $configuration) {
            $this->processProperty(
                $modelPropertiesBuilder,
                $classBuilder->getMethodsBuilder(),
                $definitionName,
                $definition,
                $propertyName,
                $configuration,
                $overwrite
            );
        }

        // Constructor
        $constructorBuilder = $this->methodFactory->createModelConstructorBuilder($classBuilder->getUsesBuilder());
        $constructorBuilder->configureFromPropertiesBuilder($modelPropertiesBuilder);
        $classBuilder->getMethodsBuilder()->addMethod($constructorBuilder);

        // File creation
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return file_put_contents($filePath, $classBuilder->build($this->indent));
    }

    /**
     * {@inheritDoc}
     */
    public function processProperty(
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        MethodsBuilderInterface $methodsBuilder,
        string $definitionName,
        array $definition,
        string $propertyName,
        array $configuration,
        bool $overwrite = false
    ): void {
        $cleanPropertyName = $this->helper::cleanStr($propertyName);

        $type = $this->generateSubClass($definitionName, $cleanPropertyName, $configuration, $overwrite);
        $type = $type === null ?
            $this->getPhpTypeFromPropertyConfig($configuration)
            : $type;

        $types = (array) $type;
        if ($this->helper::isNullableBySwaggerConfiguration($propertyName, $definition)) {
            $types[] = 'null';
        }

        $description = isset($configuration['description']) ? $configuration['description'] : null;
        $propertyBuilder = $modelPropertiesBuilder->configurePropertyFromSwaggerPropertyDefinition(
            $cleanPropertyName,
            $types,
            $description
        );

        $getterSetterBuilder = $this->methodFactory->createPropertyMethodsBuilder(
            $methodsBuilder->getUsesBuilder()
        );
        $readOnly = isset($definition['readOnly']) && $definition['readOnly'] === 'true';
        $writeOnly = isset($definition['writeOnly']) && $definition['writeOnly'] === 'true';
        $getterSetterBuilder->configure($propertyBuilder, $readOnly, $writeOnly);

        $methodBuilders = $getterSetterBuilder->getMethods($this->indent);
        $methodsBuilder->addMultipleMethod($methodBuilders);
    }

    /**
     * {@inheritDoc}
     */
    public function generateSubClass(string $currentDefinitionName, string $currentProperty, array $currentConfig, bool $overwrite = false): ?string
    {
        $type = null;
        if (isset($currentConfig['type']) && in_array($currentConfig['type'], ['object', 'array'])) {
            $subDefinitionName = $currentDefinitionName . '/' . ucfirst($currentProperty);
            $subConfig = [];
            if (isset($currentConfig['properties'])) {
                $subConfig = $currentConfig;
            } elseif (isset($currentConfig['items'], $currentConfig['items']['properties'])) {
                $subConfig = $currentConfig['items'];
            }
            if (!empty($subConfig)) {
                $this->generateClass($subDefinitionName, $subConfig, $overwrite);
                [$subNamespace, $subClassName] = $this->getClassNameAndNamespaceFromDefinitionName($subDefinitionName);
                $type = '\\' . $subNamespace . '\\' . $subClassName;
                $type .= $currentConfig['type'] === 'array' ? '[]' : '';
            }
        }

        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassNameAndNamespaceFromDefinitionName(string $definitionName, string $classPrefix = '', string $classSuffix = ''): array
    {
        $classPath = $this->helper::camelize($definitionName);
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
    protected function getFilePathFromDefinitionName(string $definitionName): string
    {
        return sprintf('%s/%s.php', $this->folder, $this->helper::camelize($definitionName));
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpTypeFromPropertyConfig(array $config): string
    {
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($config);

        if (false === $this->hasDefinition($type)) {
            return $type;
        }

        if (1 === preg_match('#^\\\\#', $type)) {
            return $type;
        }

        $singleType = rtrim($type, '[]');
        [$propertyNamespace, $propertyClassName] = $this->getClassNameAndNamespaceFromDefinitionName($singleType);
        return $propertyNamespace . '\\' . $propertyClassName;
    }

    /**
     * {@inheritDoc}
     */
    public function hasDefinition(string $definitionName): bool
    {
        return isset($this->definitions[$definitionName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function getHelper(): SwaggerModelHelperInterface
    {
        return $this->helper;
    }

    /**
     * {@inheritDoc}
     */
    public function setHelper(SwaggerModelHelperInterface $helper): void
    {
        $this->helper = $helper;
    }
}
