<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilderInterface;

interface SwaggerModelGeneratorInterface
{
    /**
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function configure(string $folder, string $namespace, string $indent = '    ');

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param ConstructorBuilderInterface $constructorBuilder
     * @param array $definition
     */
    public function processRequiredProperties(ClassBuilderInterface $classBuilder, ConstructorBuilderInterface $constructorBuilder, array $definition): void;

    /**
     * @param string $helper
     */
    public function setHelper(string $helper): void;

    /**
     * @param string $currentDefinitionName
     * @param string $currentProperty
     * @param array $currentConfig
     * @param bool $overwrite
     *
     * @return string|null
     */
    public function generateSubClass(string $currentDefinitionName, string $currentProperty, array $currentConfig, bool $overwrite = false): ?string;

    /**
     * @param array $config
     * @param ClassBuilderInterface $classBuilder
     *
     * @return string
     */
    public function getPhpTypeFromPropertyConfig(array $config, ClassBuilderInterface $classBuilder);

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
     * @return string
     */
    public function getHelper(): string;

    /**
     * @param string $definitionName
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return array
     */
    public function getClassNameAndNamespaceFromDefinitionName(string $definitionName, string $classPrefix = '', string $classSuffix = ''): array;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param ConstructorBuilderInterface $constructorBuilder
     * @param string $definitionName
     * @param array $definition
     * @param string $property
     * @param array $configuration
     * @param bool $overwrite
     */
    public function processProperty(ClassBuilderInterface $classBuilder, ConstructorBuilderInterface $constructorBuilder, string $definitionName, array $definition, string $property, array $configuration, bool $overwrite = false): void;

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
}