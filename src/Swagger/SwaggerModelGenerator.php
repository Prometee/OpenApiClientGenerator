<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Exception;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelperInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;
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
    /** @var bool */
    protected $overwrite = false;

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
     *
     * @throws Exception
     */
    public function generate(): bool
    {
        foreach ($this->definitions as $definitionName => $definition) {
            if (!isset($definition['type'])) {
                return false;
            }
            if ($definition['type'] !== 'object') {
                continue;
            }
            $this->generateClass($definitionName);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function generateClass(string $definitionName): ?ClassBuilderInterface
    {
        $filePath = $this->getFilePathFromDefinitionName($definitionName);
        if (!$this->overwrite && is_file($filePath)) {
            return null;
        }

        // Class
        $classBuilder = $this->classFactory->createClassBuilder();
        $this->configureClassBuilder(
            $classBuilder,
            $definitionName
        );

        // Properties
        /** @var ModelPropertiesBuilderInterface $modelPropertiesBuilder */
        $modelPropertiesBuilder = $classBuilder->getPropertiesBuilder();
        $this->configurePropertiesBuilder(
            $classBuilder,
            $modelPropertiesBuilder,
            $definitionName
        );

        // Constructor
        $constructorBuilder = $this->methodFactory->createModelConstructorBuilder($classBuilder->getUsesBuilder());
        $this->configureConstructorBuilder(
            $classBuilder->getMethodsBuilder(),
            $modelPropertiesBuilder,
            $constructorBuilder
        );

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
     *
     * @throws Exception
     */
    public function generateSubClass(
        string $currentDefinitionName,
        string $currentProperty,
        array $currentConfig
    ): ?string
    {
        if (!isset($currentConfig['type'])) {
            return null;
        }

        $subConfig = null;
        if ($currentConfig['type'] === 'object') {
            $subConfig = $currentConfig;
        }
        if ($currentConfig['type'] === 'array') {
            $subConfig = $currentConfig['items'];
        }

        if (null === $subConfig) {
            return null;
        }

        $subDefinitionName = $currentDefinitionName . '/' . ucfirst($currentProperty);
        $this->definitions[$subDefinitionName] = $subConfig;
        $this->generateClass($subDefinitionName);
        [$subNamespace, $subClassName] = $this->getClassNameAndNamespaceFromDefinitionName($subDefinitionName);
        $type = '\\' . $subNamespace . '\\' . $subClassName;
        $type .= $currentConfig['type'] === 'array' ? '[]' : '';

        return $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassNameAndNamespaceFromDefinitionName(
        string $definitionName,
        string $classPrefix = '',
        string $classSuffix = ''
    ): array
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

        if (null === $type) {
            return 'null';
        }

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
     *
     * @throws Exception
     */
    public function configureClassBuilder(
        ClassBuilderInterface $classBuilder,
        string $definitionName
    ): ?ClassBuilderInterface
    {
        $subClassBuilder = null;
        $extendClass = null;
        if (isset($this->definitions[$definitionName]['allOf'])) {
            $allOfConfig = $this->definitions[$definitionName]['allOf'][0];
            $subDefinitionName = $this->helper::getPhpTypeFromSwaggerConfiguration($allOfConfig);
            if (null === $subDefinitionName) {
                return null;
            }
            $subClassBuilder = $this->generateClass($subDefinitionName);
            $extendClass = $this->getPhpTypeFromPropertyConfig($allOfConfig);
        }
        [$namespace, $className] = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);
        $classBuilder->configure($namespace, $className, $extendClass);

        return $subClassBuilder;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function configurePropertiesBuilder(
        ClassBuilderInterface $classBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        string $definitionName
    ): void
    {
        $properties = $this->flattenPropertiesDefinition($this->definitions[$definitionName]);
        $requires = $this->flattenRequiresDefinition($this->definitions[$definitionName]);
        $ownedProperties = $this->foundNotInheritedProperties($this->definitions[$definitionName]);
        $inheritedProperties = array_diff(array_keys($properties), array_keys($ownedProperties));

        foreach ($properties as $propertyName => $configuration) {
            $required = false !== array_search($propertyName, $requires);
            $inherited = false !== array_search($propertyName, $inheritedProperties);
            $this->processProperty(
                $modelPropertiesBuilder,
                $classBuilder->getMethodsBuilder(),
                $definitionName,
                $propertyName,
                $configuration,
                $required,
                $inherited
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function flattenPropertiesDefinition(array $definition): array
    {
        if (isset($definition['properties'])) {
            return $definition['properties'];
        }

        if (!isset($definition['allOf'])) {
            return [];
        }

        $allOf = $definition['allOf'];
        $inheritedPropertyName = $this->helper::getPhpTypeFromSwaggerDefinitionName($allOf[0]['$ref']);

        $properties = [];
        if (isset($allOf[1]['properties'])) {
            $properties = $allOf[1]['properties'];
        }

        $inheritedProperties = $this->flattenPropertiesDefinition($this->definitions[$inheritedPropertyName]);
        return array_merge($properties, $inheritedProperties);
    }

    /**
     * {@inheritDoc}
     */
    public function flattenRequiresDefinition(array $definition): array
    {
        if (isset($definition['required'])) {
            return $definition['required'];
        }

        if (!isset($definition['allOf'])) {
            return [];
        }

        $allOf = $definition['allOf'];
        $inheritedPropertyName = $this->helper::getPhpTypeFromSwaggerDefinitionName($allOf[0]['$ref']);

        $requires = [];
        if (isset($allOf[1]['required'])) {
            $requires = $allOf[1]['required'];
        }

        $inheritedRequires = $this->flattenRequiresDefinition($this->definitions[$inheritedPropertyName]);
        return array_merge($requires, $inheritedRequires);
    }

    public function foundNotInheritedProperties(array $definition): array
    {
        if (isset($definition['properties'])) {
            return $definition['properties'];
        }

        if (!isset($definition['allOf'])) {
            return [];
        }

        $allOf = $definition['allOf'];
        if (!isset($allOf[1]['properties'])) {
            return [];
        }

        return $allOf[1]['properties'];
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function processProperty(
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        MethodsBuilderInterface $methodsBuilder,
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): void {
        $types = $this->findPropertyTypes(
            $definitionName,
            $propertyName,
            $configuration
        );

        $cleanPropertyName = $this->helper::cleanStr($propertyName);
        $description = isset($configuration['description']) ? $configuration['description'] : null;

        /** @var ModelPropertyBuilderInterface $propertyBuilder */
        $propertyBuilder = $this->classFactory->createPropertyBuilder(
            $modelPropertiesBuilder->getUsesBuilder()
        );
        $modelPropertiesBuilder->addPropertyFromSwaggerPropertyDefinition(
            $propertyBuilder,
            $cleanPropertyName,
            $types,
            $required,
            $inherited,
            $description
        );

        if ($inherited) {
            return;
        }

        $getterSetterBuilder = $this->methodFactory->createPropertyMethodsBuilder(
            $methodsBuilder->getUsesBuilder()
        );
        $definition = &$this->definitions[$definitionName];
        $readOnly = isset($definition['readOnly']) && $definition['readOnly'] === 'true';
        $writeOnly = isset($definition['writeOnly']) && $definition['writeOnly'] === 'true';
        $getterSetterBuilder->configure($propertyBuilder, $readOnly, $writeOnly);

        $methodBuilders = $getterSetterBuilder->getMethods($this->methodFactory, $this->indent);
        $methodsBuilder->addMultipleMethod($methodBuilders);
    }

    /**
     * {@inheritDoc}
     */
    public function configureConstructorBuilder(
        MethodsBuilderInterface $methodsBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        ModelConstructorBuilderInterface $constructorBuilder
    ): void
    {
        $constructorBuilder->configureFromPropertiesBuilder($modelPropertiesBuilder);
        $methodsBuilder->addMethod($constructorBuilder);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function findPropertyTypes(
        string $definitionName,
        string $propertyName,
        array $configuration
    ): array
    {
        $cleanPropertyName = $this->helper::cleanStr($propertyName);

        $type = $this->generateSubClass($definitionName, $cleanPropertyName, $configuration);
        if (null === $type) {
            $type = $this->getPhpTypeFromPropertyConfig($configuration);
        }

        $types = [$type];

        if ($this->helper::isNullableBySwaggerConfiguration($propertyName, $this->definitions[$definitionName])) {
            $types[] = 'null';
        }

        return $types;
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
