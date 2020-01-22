<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelperInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other\ModelPropertiesBuilderInterface;

interface SwaggerModelGeneratorInterface
{
    /**
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function configure(string $folder, string $namespace, string $indent = '    '): void;

    /**
     * @param SwaggerModelHelperInterface $helper
     */
    public function setHelper(SwaggerModelHelperInterface $helper): void;

    /**
     * @param string $currentDefinitionName
     * @param string $currentProperty
     * @param array $currentConfig
     * @param bool $overwrite
     *
     * @return string|null
     */
    public function generateSubClass(
        string $currentDefinitionName,
        string $currentProperty,
        array $currentConfig,
        bool $overwrite = false
    ): ?string;

    /**
     * @param array $config
     *
     * @return string
     */
    public function getPhpTypeFromPropertyConfig(array $config): string;

    /**
     * @param array $definitions
     */
    public function setDefinitions(array $definitions): void;

    /**
     * @param bool $overwrite
     *
     * @return bool
     */
    public function generate(bool $overwrite = false): bool;

    /**
     * @return array
     */
    public function getDefinitions(): array;

    /**
     * @return SwaggerModelHelperInterface
     */
    public function getHelper(): SwaggerModelHelperInterface;

    /**
     * @param string $definitionName
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return array
     */
    public function getClassNameAndNamespaceFromDefinitionName(
        string $definitionName,
        string $classPrefix = '',
        string $classSuffix = ''
    ): array;

    /**
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     * @param MethodsBuilderInterface $methodsBuilder
     * @param string $definitionName
     * @param array $definition
     * @param string $propertyName
     * @param array $configuration
     * @param bool $overwrite
     */
    public function processProperty(
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        MethodsBuilderInterface $methodsBuilder,
        string $definitionName,
        array $definition,
        string $propertyName,
        array $configuration,
        bool $overwrite = false
    ): void;

    /**
     * @param string $definitionName
     * @param array $definition
     * @param bool $overwrite
     *
     * @return bool|int
     */
    public function generateClass(string $definitionName, array $definition, bool $overwrite = false);

    /**
     * @param string $definitionName
     *
     * @return bool
     */
    public function hasDefinition(string $definitionName): bool;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     * @param ModelConstructorBuilderInterface $constructorBuilder
     */
    public function configureConstructorBuilder(
        ClassBuilderInterface $classBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        ModelConstructorBuilderInterface $constructorBuilder
    ): void;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param string $definitionName
     * @param array $definition
     */
    public function configureClassBuilder(
        ClassBuilderInterface $classBuilder,
        string $definitionName,
        array $definition
    ): void;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     * @param string $definitionName
     * @param array $definition
     * @param bool $overwrite
     */
    public function configurePropertiesBuilder(
        ClassBuilderInterface $classBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        string $definitionName,
        array $definition,
        bool $overwrite = false
    ): void;

    /**
     * @param string $definitionName
     * @param array $definition
     * @param string $propertyName
     * @param array $configuration
     * @param bool $overwrite
     *
     * @return string[]
     */
    public function findPropertyTypes(
        string $definitionName,
        array $definition,
        string $propertyName,
        array $configuration,
        bool $overwrite = false
    ): array;
}