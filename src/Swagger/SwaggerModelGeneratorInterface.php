<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
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
     * @return bool
     */
    public function isOverwrite(): bool;

    /**
     * @param bool $overwrite
     */
    public function setOverwrite(bool $overwrite): void;

    /**
     * @param string $currentDefinitionName
     * @param string $currentProperty
     * @param array $currentConfig
     *
     * @return string|null
     */
    public function generateSubClass(
        string $currentDefinitionName,
        string $currentProperty,
        array $currentConfig
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
     * @return bool
     */
    public function generate(): bool;

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
     * @param string $propertyName
     * @param array $configuration
     * @param bool $required
     * @param bool $inherited
     */
    public function processProperty(
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        MethodsBuilderInterface $methodsBuilder,
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): void;

    /**
     * @param string $definitionName
     *
     * @return null|ClassBuilderInterface
     */
    public function generateClass(string $definitionName): ?ClassBuilderInterface;

    /**
     * @param string $definitionName
     *
     * @return bool
     */
    public function hasDefinition(string $definitionName): bool;

    /**
     * @param MethodsBuilderInterface $methodsBuilder
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     * @param ModelConstructorBuilderInterface $constructorBuilder
     */
    public function configureConstructorBuilder(
        MethodsBuilderInterface $methodsBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        ModelConstructorBuilderInterface $constructorBuilder
    ): void;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param string $definitionName
     *
     * @return ClassBuilderInterface|null
     */
    public function configureClassBuilder(
        ClassBuilderInterface $classBuilder,
        string $definitionName
    ): ?ClassBuilderInterface;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     * @param string $definitionName
     */
    public function configurePropertiesBuilder(
        ClassBuilderInterface $classBuilder,
        ModelPropertiesBuilderInterface $modelPropertiesBuilder,
        string $definitionName
    ): void;

    /**
     * @param array $definition
     *
     * @return array
     */
    public function flattenPropertiesDefinition(array $definition): array;

    /**
     * @param array $definition
     *
     * @return string[]
     */
    public function flattenRequiresDefinition(array $definition): array;

    /**
     * @param string $definitionName
     * @param string $propertyName
     * @param array $configuration
     *
     * @return string[]
     */
    public function findPropertyTypes(
        string $definitionName,
        string $propertyName,
        array $configuration
    ): array;
}