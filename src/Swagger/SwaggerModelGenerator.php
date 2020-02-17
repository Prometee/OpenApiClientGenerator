<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerModelHelperInterface;

class SwaggerModelGenerator implements SwaggerModelGeneratorInterface
{
    /** @var ModelClassGeneratorFactoryInterface */
    protected $modelClassGeneratorFactory;
    /** @var ModelMethodGeneratorFactoryInterface */
    protected $modelMethodGeneratorFactory;
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
     * @param ModelClassGeneratorFactoryInterface $modelClassGeneratorFactory
     * @param ModelMethodGeneratorFactoryInterface $modelMethodGeneratorFactory
     * @param SwaggerModelHelperInterface $helper
     */
    public function __construct(
        ModelClassGeneratorFactoryInterface $modelClassGeneratorFactory,
        ModelMethodGeneratorFactoryInterface $modelMethodGeneratorFactory,
        SwaggerModelHelperInterface $helper
    )
    {
        $this->modelClassGeneratorFactory = $modelClassGeneratorFactory;
        $this->modelMethodGeneratorFactory = $modelMethodGeneratorFactory;
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
    public function generateClass(string $definitionName): void
    {
        $filePath = $this->getFilePathFromDefinitionName($definitionName);
        if (!$this->overwrite && is_file($filePath)) {
            return;
        }

        // Class
        $classGenerator = $this->modelClassGeneratorFactory->createClassGenerator();
        $this->configureClassGenerator(
            $classGenerator,
            $definitionName
        );

        // Properties
        /** @var ModelPropertiesGeneratorInterface $modelPropertiesGenerator */
        $modelPropertiesGenerator = $classGenerator->getPropertiesGenerator();
        $this->configurePropertiesGenerator(
            $classGenerator,
            $modelPropertiesGenerator,
            $definitionName
        );

        // Constructor
        /** @var ModelConstructorGeneratorInterface $modelConstructorGenerator */
        $modelConstructorGenerator = $this->modelMethodGeneratorFactory->createModelConstructorGenerator($classGenerator->getUsesGenerator());
        $this->configureConstructorGenerator(
            $classGenerator->getMethodsGenerator(),
            $modelPropertiesGenerator,
            $modelConstructorGenerator
        );

        // File creation
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (false === file_put_contents($filePath, $classGenerator->generate($this->indent))) {
            throw new Exception(sprintf('Unable to generate the class : "%s" !', $filePath));
        }
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
            $subConfig = $this->helper::getArrayEmbeddedObjectConfig($currentConfig);
        }

        if (null === $subConfig) {
            return null;
        }

        $subDefinitionName = sprintf('%s/%s', $currentDefinitionName, ucfirst($currentProperty));
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
        $namespace = $this->namespace . ($namespace === '' ? '' : '\\'.$namespace);

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
    public function configureClassGenerator(
        ClassGeneratorInterface $classGenerator,
        string $definitionName
    ): void
    {
        $extendClass = null;
        if (isset($this->definitions[$definitionName]['allOf'])) {
            $allOfConfig = $this->definitions[$definitionName]['allOf'][0];
            $subDefinitionName = $this->helper::getPhpTypeFromSwaggerConfiguration($allOfConfig);
            if (null === $subDefinitionName) {
                return;
            }
            $this->generateClass($subDefinitionName);
            $extendClass = $this->getPhpTypeFromPropertyConfig($allOfConfig);
        }
        [$namespace, $className] = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);
        $classGenerator->configure($namespace, $className, $extendClass);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function configurePropertiesGenerator(
        ClassGeneratorInterface $classGenerator,
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
        string $definitionName
    ): void
    {
        $properties = $this->helper::flattenDefinitionType('properties', $this->definitions, $definitionName);
        $requires = $this->helper::flattenDefinitionType('required', $this->definitions, $definitionName);
        $ownedProperties = $this->helper::foundNotInheritedProperties($this->definitions[$definitionName]);
        $inheritedProperties = array_diff(array_keys($properties), array_keys($ownedProperties));

        foreach ($properties as $propertyName => $configuration) {
            $required = false !== array_search($propertyName, $requires);
            $inherited = false !== array_search($propertyName, $inheritedProperties);
            $this->processProperty(
                $modelPropertiesGenerator,
                $classGenerator->getMethodsGenerator(),
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
     *
     * @throws Exception
     */
    public function processProperty(
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
        MethodsGeneratorInterface $methodsGenerator,
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

        /** @var ModelPropertyGeneratorInterface $propertyGenerator */
        $propertyGenerator = $this->modelClassGeneratorFactory->createPropertyGenerator(
            $modelPropertiesGenerator->getUsesGenerator()
        );
        $modelPropertiesGenerator->addPropertyFromSwaggerPropertyDefinition(
            $propertyGenerator,
            $cleanPropertyName,
            $types,
            $required,
            $inherited,
            $description
        );

        if ($inherited) {
            return;
        }

        $getterSetterGenerator = $this->modelMethodGeneratorFactory->createPropertyMethodsGenerator(
            $methodsGenerator->getUsesGenerator()
        );
        $definition = &$this->definitions[$definitionName];
        $readOnly = isset($definition['readOnly']) && $definition['readOnly'] === 'true';
        $writeOnly = isset($definition['writeOnly']) && $definition['writeOnly'] === 'true';
        $getterSetterGenerator->configure($propertyGenerator, $readOnly, $writeOnly);

        $methodGenerators = $getterSetterGenerator->getMethods($this->modelMethodGeneratorFactory, $this->indent);
        $methodsGenerator->addMultipleMethod($methodGenerators);
    }

    /**
     * {@inheritDoc}
     */
    public function configureConstructorGenerator(
        MethodsGeneratorInterface $methodsGenerator,
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
        ModelConstructorGeneratorInterface $constructorGenerator
    ): void
    {
        $constructorGenerator->configureFromPropertiesGenerator($modelPropertiesGenerator);
        $methodsGenerator->addMethod($constructorGenerator);
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
