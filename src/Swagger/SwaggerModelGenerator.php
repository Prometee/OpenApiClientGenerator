<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\ClassBuilder;
use Prometee\SwaggerClientBuilder\Method\ConstructorBuilder;
use Prometee\SwaggerClientBuilder\Method\MethodParameterBuilder;
use Prometee\SwaggerClientBuilder\Method\PropertyMethodsBuilder;
use Prometee\SwaggerClientBuilder\Property\PropertyBuilder;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelper;

class SwaggerModelGenerator
{
    /** @var string */
    protected $folder;
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $indent;

    /** @var array */
    protected $definitions;

    /** @var string */
    protected $helper;

    /**
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function __construct(string $folder, string $namespace, string $indent = "    ")
    {
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->indent = $indent;
        $this->definitions = [];
        $this->helper = SwaggerModelHelper::class;
    }

    /**
     * @param bool $overwrite
     * @return bool
     */
    public function generate(bool $overwrite = false): bool
    {
        foreach ($this->definitions as $definitionName=>$definition) {
            if (!isset($definition['type'])) return false;
            if (!isset($definition['properties'])) continue;
            if ($definition['type'] !== 'object') continue;

            $this->generateClass($definitionName, $definition, $overwrite);
        }

        return true;
    }

    /**
     * @param string $definitionName
     * @param array $definition
     * @param bool $overwrite
     * @return bool|int
     */
    public function generateClass(string $definitionName, array $definition, bool $overwrite = false)
    {
        $filePath = $this->getFilePathFromDefinitionName($definitionName);
        if (!$overwrite && is_file($filePath)) return false;

        list($namespace, $className) = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);

        $classBuilder = new ClassBuilder($namespace, $className);
        $constructorBuilder = new ConstructorBuilder();
        $classBuilder->getMethodsBuilder()->addMethod($constructorBuilder);

        foreach ($definition['properties'] as $property=>$config) {
            $this->processProperty(
                $classBuilder,
                $constructorBuilder,
                $definitionName,
                $definition,
                $property,
                $config,
                $overwrite
            );
        }

        $this->processRequiredProperties($classBuilder, $constructorBuilder, $definition);

        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return file_put_contents($filePath, $classBuilder->build($this->indent));
    }

    /**
     * @param ClassBuilder $classBuilder
     * @param ConstructorBuilder $constructorBuilder
     * @param string $definitionName
     * @param array $definition
     * @param string $property
     * @param array $configuration
     * @param bool $overwrite
     */
    public function processProperty(
        ClassBuilder $classBuilder,
        ConstructorBuilder $constructorBuilder,
        string $definitionName,
        array $definition,
        string $property,
        array $configuration,
        bool $overwrite = false
    ): void
    {
        $cleanPropertyName = $this->helper::cleanPropertyName($property);

        $type = $this->generateSubClass($definitionName, $cleanPropertyName, $configuration, $overwrite);
        $type = $type === null ?
            $this->getPhpTypeFromPropertyConfig($configuration, $classBuilder)
            : $type;

        if (preg_match('#^\\\\#', $type)) {
            $classBuilder->getUsesBuilder()->addUse(trim($type, '\\[]'));
            $types = explode('\\', trim($type, '\\'));
            $type = end($types);
        }

        if ($this->helper::isNullableBySwaggerConfiguration($property, $definition)) {
            $type .= '|null';
        }

        $propertyBuilder = new PropertyBuilder($cleanPropertyName, $type);
        if (isset($configuration['description'])) {
            $propertyBuilder->setDescription($configuration['description']);
        }
        $classBuilder->getPropertiesBuilder()->addProperty($propertyBuilder);

        $getterSetterBuilder = new PropertyMethodsBuilder($propertyBuilder);
        if (isset($definition['readOnly']) && $definition['readOnly'] === 'true') {
            $getterSetterBuilder->setReadOnly(true);
        }
        if (isset($definition['writeOnly']) && $definition['writeOnly'] === 'true') {
            $getterSetterBuilder->setWriteOnly(true);
        }

        switch ($propertyBuilder->getPhpType()) {
            case 'array':
                $constructorBuilder->addLine(sprintf('$this->%s = [];', $cleanPropertyName));
                break;
            case 'string':
                $constructorBuilder->addLine(sprintf('$this->%s = \'\';', $cleanPropertyName));
                break;
            case 'bool':
                $constructorBuilder->addLine(sprintf('$this->%s = true;', $cleanPropertyName));
                break;
            case 'int':
                $constructorBuilder->addLine(sprintf('$this->%s = 0;', $cleanPropertyName));
                break;
            case 'float':
                $constructorBuilder->addLine(sprintf('$this->%s = .0;', $cleanPropertyName));
                break;
        }

        $classBuilder->getMethodsBuilder()->addMultipleMethod($getterSetterBuilder->getMethods($this->indent));
    }

    /**
     * @param string $currentDefinitionName
     * @param string $currentProperty
     * @param array $currentConfig
     * @param bool $overwrite
     * @return string|null
     */
    public function generateSubClass(string $currentDefinitionName, string $currentProperty, array $currentConfig, bool $overwrite = false): ?string
    {
        $type = null;
        if (isset($currentConfig['type']) && in_array($currentConfig['type'], ['object', 'array'])) {
            $subDefinitionName = $currentDefinitionName . '/' . ucfirst($currentProperty);
            $subConfig = [];
            if (isset($currentConfig['properties'])) {
                $subConfig = $currentConfig;
            } else if (isset($currentConfig['items']) && isset($currentConfig['items']['properties'])) {
                $subConfig = $currentConfig['items'];
            }
            if (!empty($subConfig)) {
                $this->generateClass($subDefinitionName, $subConfig, $overwrite);
                list($subNamespace, $subClassName) = $this->getClassNameAndNamespaceFromDefinitionName($subDefinitionName);
                $type = '\\' . $subNamespace . '\\' . $subClassName;
                $type .= $currentConfig['type'] === 'array' ? '[]' : '';
            }
        }

        return $type;
    }

    /**
     * @param ClassBuilder $classBuilder
     * @param ConstructorBuilder $constructorBuilder
     * @param array $definition
     */
    public function processRequiredProperties(ClassBuilder $classBuilder, ConstructorBuilder $constructorBuilder, array $definition): void
    {
        if (isset($definition['required'])) {
            foreach ($definition['required'] as $property) {
                $property = $this->helper::cleanPropertyName($property);
                $propertyBuilder = $classBuilder->getPropertiesBuilder()->getPropertyByName($property);
                $type = ($propertyBuilder !== null) ? $propertyBuilder->getType() : '';
                $description = $propertyBuilder->getDescription();
                $constructorBuilder->addParameter(new MethodParameterBuilder($type, $property, null, false, $description));
                $constructorBuilder->addLine(sprintf('$this->%1$s = $%1$s;', $property));
            }
        }
    }

    /**
     * @param string $definitionName
     * @param string $classPrefix
     * @param string $classSuffix
     * @return array
     */
    public function getClassNameAndNamespaceFromDefinitionName(string $definitionName, string $classPrefix = '', string $classSuffix = ''): array
    {
        $className = $this->helper::getClassPathFromDefinitionName($definitionName);
        $namespace = $this->namespace.'\\'.preg_replace('#/#', '\\', $className);
        $className = basename($className);
        $namespace = preg_replace(
            '#\\\\'.$className.'$#',
            '',
            $namespace
        );

        return [
            $namespace,
            $classPrefix.$className.$classSuffix,
        ];
    }

    /**
     * @param string $definitionName
     * @return string
     */
    protected function getFilePathFromDefinitionName(string $definitionName): string
    {
        return sprintf('%s/%s.php', $this->folder, $this->helper::getClassPathFromDefinitionName($definitionName));
    }

    /**
     * @param array $config
     * @param ClassBuilder $classBuilder
     * @return string
     */
    public function getPhpTypeFromPropertyConfig(array $config, ClassBuilder $classBuilder)
    {
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($config);
        $currentNamespace= $classBuilder->getNamespace();
        if ($this->hasDefinition($type)) {
            if (preg_match('#^\\\\#', $type) === 0) {
                list($propertyNamespace, $propertyClassName) = $this->getClassNameAndNamespaceFromDefinitionName(trim($type, '[]'));
                if ($currentNamespace !== $propertyNamespace) {
                    $classBuilder->getUsesBuilder()->addUse($propertyNamespace . '\\' . $propertyClassName);
                }
                $type = $propertyClassName;
            }
        }
        return $type;
    }

    /**
     * @param string $definitionName
     * @return bool
     */
    public function hasDefinition(string $definitionName): bool
    {
        return isset($this->definitions[$definitionName]);
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array $definitions
     */
    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
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
}
