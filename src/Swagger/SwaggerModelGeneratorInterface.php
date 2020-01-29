<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerModelHelperInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;

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
     * @param ModelPropertiesGeneratorInterface $modelPropertiesBuilder
     * @param MethodsGeneratorInterface $methodsBuilder
     * @param string $definitionName
     * @param string $propertyName
     * @param array $configuration
     * @param bool $required
     * @param bool $inherited
     */
    public function processProperty(
        ModelPropertiesGeneratorInterface $modelPropertiesBuilder,
        MethodsGeneratorInterface $methodsBuilder,
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): void;

    /**
     * @param string $definitionName
     *
     * @return null|ClassGeneratorInterface
     */
    public function generateClass(string $definitionName): ?ClassGeneratorInterface;

    /**
     * @param string $definitionName
     *
     * @return bool
     */
    public function hasDefinition(string $definitionName): bool;

    /**
     * @param MethodsGeneratorInterface $methodsBuilder
     * @param ModelPropertiesGeneratorInterface $modelPropertiesBuilder
     * @param ModelConstructorGeneratorInterface $constructorBuilder
     */
    public function configureConstructorBuilder(
        MethodsGeneratorInterface $methodsBuilder,
        ModelPropertiesGeneratorInterface $modelPropertiesBuilder,
        ModelConstructorGeneratorInterface $constructorBuilder
    ): void;

    /**
     * @param ClassGeneratorInterface $classBuilder
     * @param string $definitionName
     *
     * @return ClassGeneratorInterface|null
     */
    public function configureClassBuilder(
        ClassGeneratorInterface $classBuilder,
        string $definitionName
    ): ?ClassGeneratorInterface;

    /**
     * @param ClassGeneratorInterface $classBuilder
     * @param ModelPropertiesGeneratorInterface $modelPropertiesBuilder
     * @param string $definitionName
     */
    public function configurePropertiesBuilder(
        ClassGeneratorInterface $classBuilder,
        ModelPropertiesGeneratorInterface $modelPropertiesBuilder,
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