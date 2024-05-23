<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Converter;

use Exception;
use LogicException;
use Prometee\PhpClassGenerator\Builder\ClassBuilderInterface;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\ModelHelperInterface;

class ModelConverter implements ModelConverterInterface
{
    protected array $definitions = [];

    protected array $generatedConfig = [];

    public function __construct(
        protected string $modelNamespacePrefix,
        protected string $namespace,
        protected ModelHelperInterface $helper
    ) {
    }

    public function convert(array $definitions): array
    {
        $this->setDefinitions($definitions);

        $this->generatedConfig = [];
        foreach ($this->definitions as $definitionName => $definition) {
            if (isset($definition['type']) && $definition['type'] !== 'object') {
                continue;
            }

            $this->generatedConfig[] = $this->generateClass($definitionName);
        }

        return $this->generatedConfig;
    }

    public function generateClass(string $definitionName): array
    {
        // Class
        $config = $this->convertClass($definitionName);
        $config['properties'] = $this->convertProperties($definitionName);

        return $config;
    }

    /**
     * @throws Exception
     */
    public function generateSubClass(
        string $currentDefinitionName,
        string $currentProperty,
        array $currentConfig
    ): ?string {
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
        $this->generatedConfig[] = $this->generateClass($subDefinitionName);
        [$subNamespace, $subClassName] = $this->getClassNameAndNamespaceFromDefinitionName($subDefinitionName);
        $type = '\\' . $subNamespace . '\\' . $subClassName;
        $type .= $currentConfig['type'] === 'array' ? '[]' : '';

        return $type;
    }

    public function getClassNameAndNamespaceFromDefinitionName(
        string $definitionName,
        string $classPrefix = '',
        string $classSuffix = ''
    ): array {
        $classPath = $this->helper::camelize($definitionName);
        $classParts = explode('/', $classPath);
        $className = array_pop($classParts);
        $namespace = implode('\\', $classParts);
        $namespace = $this->modelNamespacePrefix . ($namespace === '' ? '' : '\\' . $namespace);

        return [
            $namespace,
            $classPrefix . $className . $classSuffix,
        ];
    }

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

    public function convertClass(string $definitionName): array
    {
        $extendClass = null;
        if (isset($this->definitions[$definitionName]['allOf'])) {
            $allOfConfig = $this->definitions[$definitionName]['allOf'][0];
            $subDefinitionName = $this->helper::getPhpTypeFromSwaggerConfiguration($allOfConfig);
            if (null === $subDefinitionName) {
                throw new LogicException(sprintf('Unable to find the sub definition of "%s".', $allOfConfig));
            }
            $this->generatedConfig[] = $this->generateClass($subDefinitionName);
            $extendClass = $this->getPhpTypeFromPropertyConfig($allOfConfig);
            $extendClass = $this->namespace . "\\" . $extendClass;
        }

        [$namespace, $className] = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);

        return [
            'class' => $namespace . '\\' . $className,
            'type' => ClassBuilderInterface::CLASS_TYPE_CLASS,
            'extends' => $extendClass,
        ];
    }

    public function convertProperties(string $definitionName): array
    {
        $properties = $this->helper::flattenDefinitionType('properties', $this->definitions, $definitionName);
        $requires = $this->helper::flattenDefinitionType('required', $this->definitions, $definitionName);
        $ownedProperties = $this->helper::foundNotInheritedProperties($this->definitions[$definitionName]);
        $inheritedProperties = array_diff(array_keys($properties), array_keys($ownedProperties));

        $propertiesConfig = [];
        foreach ($properties as $propertyName => $configuration) {
            $required = in_array($propertyName, $requires, true);
            $inherited = in_array($propertyName, $inheritedProperties, true);
            $propertiesConfig[] = $this->processProperty(
                $definitionName,
                $propertyName,
                $configuration,
                $required,
                $inherited
            );
        }

        return $propertiesConfig;
    }

    public function processProperty(
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): array {
        $types = $this->findPropertyTypes(
            $definitionName,
            $propertyName,
            $configuration
        );

        $cleanPropertyName = $this->helper::cleanStr($propertyName);
        $description = $configuration['description'] ?? null;

        $readOnly = $configuration['readOnly'] ?? false;
        $writeOnly = $configuration['writeOnly'] ?? false;

        return [
            'required' => $required,
            'inherited' => $inherited,
            'name' => $cleanPropertyName,
            'types' => $types,
            'description' => $description,
            'readable' => $readOnly || !$writeOnly,
            'writeable' => $writeOnly || !$readOnly,
        ];
    }

    public function findPropertyTypes(
        string $definitionName,
        string $propertyName,
        array $configuration
    ): array {
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

    public function hasDefinition(string $definitionName): bool
    {
        return isset($this->definitions[$definitionName]);
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
    }

    public function getHelper(): ModelHelperInterface
    {
        return $this->helper;
    }

    public function setHelper(ModelHelperInterface $helper): void
    {
        $this->helper = $helper;
    }

    public function getModelNamespacePrefix(): string
    {
        return $this->modelNamespacePrefix;
    }

    public function setModelNamespacePrefix(string $modelNamespacePrefix): void
    {
        $this->modelNamespacePrefix = $modelNamespacePrefix;
    }
}
