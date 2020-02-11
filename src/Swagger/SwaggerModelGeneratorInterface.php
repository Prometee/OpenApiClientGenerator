<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerModelHelperInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;

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
     * @return array|null
     */
    public function getArrayEmbeddedObjectConfig(array $config): ?array;

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
     * @param ModelPropertiesGeneratorInterface $modelPropertiesGenerator
     * @param MethodsGeneratorInterface $methodsGenerator
     * @param string $definitionName
     * @param string $propertyName
     * @param array $configuration
     * @param bool $required
     * @param bool $inherited
     */
    public function processProperty(
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
        MethodsGeneratorInterface $methodsGenerator,
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): void;

    /**
     * @param string $definitionName
     */
    public function generateClass(string $definitionName): void;

    /**
     * @param string $definitionName
     *
     * @return bool
     */
    public function hasDefinition(string $definitionName): bool;

    /**
     * @param MethodsGeneratorInterface $methodsGenerator
     * @param ModelPropertiesGeneratorInterface $modelPropertiesGenerator
     * @param ModelConstructorGeneratorInterface $constructorGenerator
     */
    public function configureConstructorGenerator(
        MethodsGeneratorInterface $methodsGenerator,
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
        ModelConstructorGeneratorInterface $constructorGenerator
    ): void;

    /**
     * @param ClassGeneratorInterface $classGenerator
     * @param ModelPropertiesGeneratorInterface $modelPropertiesGenerator
     * @param string $definitionName
     */
    public function configurePropertiesGenerator(
        ClassGeneratorInterface $classGenerator,
        ModelPropertiesGeneratorInterface $modelPropertiesGenerator,
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