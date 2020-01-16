<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelperInterface;

class SwaggerModelGenerator implements SwaggerModelGeneratorInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;
    /** @var MethodFactoryInterface */
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
     * @param ClassFactoryInterface $classFactory
     * @param MethodFactoryInterface $methodFactory
     * @param SwaggerModelHelperInterface $helper
     */
    public function __construct(
        ClassFactoryInterface $classFactory,
        MethodFactoryInterface $methodFactory,
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
        [$namespace, $className] = $this->getClassNameAndNamespaceFromDefinitionName($definitionName);

        $classBuilder = $this->classFactory->createClassBuilder();
        $classBuilder->configure($namespace, $className);
        $constructorBuilder = $this->methodFactory->createConstructorBuilder(
            $classBuilder->getUsesBuilder()
        );
        $classBuilder->getMethodsBuilder()->addMethod($constructorBuilder);

        $this->processProperties($definitionName, $definition, $classBuilder, $constructorBuilder, $overwrite);

        $this->processRequiredProperties($classBuilder, $constructorBuilder, $definition);

        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return file_put_contents($filePath, $classBuilder->build($this->indent));
    }

    /**
     * @param string $definitionName
     * @param array $definition
     * @param ClassBuilderInterface $classBuilder
     * @param ConstructorBuilderInterface $constructorBuilder
     * @param bool $overwrite
     */
    public function processProperties(
        string $definitionName,
        array $definition,
        ClassBuilderInterface $classBuilder,
        ConstructorBuilderInterface $constructorBuilder,
        bool $overwrite = false
    ): void
    {
        if (isset($definition['properties'])) {
            $properties = $definition['properties'];
        } else {
            $properties = $definition['allOf'][1]['properties'];
            $type = $this->getPhpTypeFromPropertyConfig($definition['allOf'][0], $classBuilder);
            $classBuilder->setExtendClassName($type);
        }

        foreach ($properties as $property => $config) {
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
    }

    /**
     * {@inheritDoc}
     */
    public function processProperty(
        ClassBuilderInterface $classBuilder,
        ConstructorBuilderInterface $constructorBuilder,
        string $definitionName,
        array $definition,
        string $property,
        array $configuration,
        bool $overwrite = false
    ): void {
        $cleanPropertyName = $this->helper::cleanStr($property);

        $type = $this->generateSubClass($definitionName, $cleanPropertyName, $configuration, $overwrite);
        $type = $type === null ?
            $this->getPhpTypeFromPropertyConfig($configuration, $classBuilder)
            : $type;

        $types = (array) $type;
        if ($this->helper::isNullableBySwaggerConfiguration($property, $definition)) {
            $types[] = 'null';
        }

        $propertyBuilder = $this->classFactory->createPropertyBuilder(
            $classBuilder->getUsesBuilder()
        );
        $propertyBuilder->configure($cleanPropertyName, $types);
        if (isset($configuration['description'])) {
            $propertyBuilder->setDescription($configuration['description']);
        }
        $classBuilder->getPropertiesBuilder()->addProperty($propertyBuilder);

        $getterSetterBuilder = $this->methodFactory->createPropertyMethodsBuilder(
            $classBuilder->getUsesBuilder()
        );
        $getterSetterBuilder->configure($propertyBuilder);
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
    public function processRequiredProperties(ClassBuilderInterface $classBuilder, ConstructorBuilderInterface $constructorBuilder, array $definition): void
    {
        if (isset($definition['required'])) {
            foreach ($definition['required'] as $property) {
                $property = $this->helper::cleanStr($property);
                $propertyBuilder = $classBuilder->getPropertiesBuilder()->getPropertyByName($property);
                $type = ($propertyBuilder !== null) ? $propertyBuilder->getType() : '';
                $description = $propertyBuilder->getDescription();
                $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
                    $classBuilder->getUsesBuilder()
                );
                $methodParameterBuilder->configure(
                    (array) $type,
                    $property,
                    null,
                    false,
                    $description
                );
                $constructorBuilder->addParameter($methodParameterBuilder);
                $constructorBuilder->addLine(sprintf('$this->%1$s = $%1$s;', $property));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getClassNameAndNamespaceFromDefinitionName(string $definitionName, string $classPrefix = '', string $classSuffix = ''): array
    {
        $className = $this->helper::camelize($definitionName);
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
    protected function getFilePathFromDefinitionName(string $definitionName): string
    {
        return sprintf('%s/%s.php', $this->folder, $this->helper::camelize($definitionName));
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpTypeFromPropertyConfig(array $config, ClassBuilderInterface $classBuilder): string
    {
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($config);

        if (false === $this->hasDefinition($type)) {
            return $type;
        }

        if (1 === preg_match('#^\\\\#', $type)) {
            return $type;
        }

        $singleType = trim($type, '[]');
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
