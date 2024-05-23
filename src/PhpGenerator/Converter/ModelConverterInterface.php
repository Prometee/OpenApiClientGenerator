<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Converter;

use Prometee\SwaggerClientGenerator\OpenApi\Helper\ModelHelperInterface;

interface ModelConverterInterface
{
    public function setHelper(ModelHelperInterface $helper): void;


    public function generateSubClass(
        string $currentDefinitionName,
        string $currentProperty,
        array $currentConfig
    ): ?string;


    public function getPhpTypeFromPropertyConfig(array $config): string;

    public function setDefinitions(array $definitions): void;

    public function convert(array $definitions): array;

    public function getDefinitions(): array;

    public function getHelper(): ModelHelperInterface;


    public function getClassNameAndNamespaceFromDefinitionName(
        string $definitionName,
        string $classPrefix = '',
        string $classSuffix = ''
    ): array;

    public function processProperty(
        string $definitionName,
        string $propertyName,
        array $configuration,
        bool $required = false,
        bool $inherited = false
    ): array;

    public function generateClass(string $definitionName): array;


    public function hasDefinition(string $definitionName): bool;

    public function convertProperties(string $definitionName): array;

    /**
     * @return string[]
     */
    public function findPropertyTypes(
        string $definitionName,
        string $propertyName,
        array $configuration
    ): array;

    public function getModelNamespacePrefix(): string;

    public function setModelNamespacePrefix(string $modelNamespacePrefix): void;
}
