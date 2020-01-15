<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelper;

class SwaggerModelGenerator implements SwaggerModelGeneratorInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;
    /** @var MethodFactoryInterface */
    protected $methodFactory;
    /** @var callable|SwaggerModelHelper */
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
     */
    public function __construct(
        ClassFactoryInterface $classFactory,
        MethodFactoryInterface $methodFactory
    )
    {
        $this->classFactory = $classFactory;
        $this->methodFactory = $methodFactory;
        $this->helper = SwaggerModelHelper::class;
    }

    /**
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function configure(string $folder, string $namespace, string $indent = '    ')
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
            if (!isset($definition['properties'])) {
                continue;
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

        foreach ($definition['properties'] as $property => $config) {
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

        $propertyBuilder = $this->classFactory->createPropertyBuilder(
            $classBuilder->getUsesBuilder()
        );
        $propertyBuilder->configure($cleanPropertyName, $type);
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
                $property = $this->helper::cleanPropertyName($property);
                $propertyBuilder = $classBuilder->getPropertiesBuilder()->getPropertyByName($property);
                $type = ($propertyBuilder !== null) ? $propertyBuilder->getType() : '';
                $description = $propertyBuilder->getDescription();
                $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
                    $classBuilder->getUsesBuilder()
                );
                $methodParameterBuilder->configure($type, $property, null, false, $description);
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
        $className = $this->helper::getClassNameFromDefinitionName($definitionName);
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
        return sprintf('%s/%s.php', $this->folder, $this->helper::getClassNameFromDefinitionName($definitionName));
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpTypeFromPropertyConfig(array $config, ClassBuilderInterface $classBuilder)
    {
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($config);
        $currentNamespace = $classBuilder->getNamespace();
        if ($this->hasDefinition($type)) {
            if (preg_match('#^\\\\#', $type) === 0) {
                [$propertyNamespace, $propertyClassName] = $this->getClassNameAndNamespaceFromDefinitionName(trim($type, '[]'));
                if ($currentNamespace !== $propertyNamespace) {
                    $classBuilder->getUsesBuilder()->addUse($propertyNamespace . '\\' . $propertyClassName);
                }
                $type = $propertyClassName;
            }
        }

        return $type;
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
    public function getHelper(): string
    {
        return $this->helper;
    }

    /**
     * {@inheritDoc}
     */
    public function setHelper(string $helper): void
    {
        $this->helper = $helper;
    }
}
