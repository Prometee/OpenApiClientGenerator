<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

interface ModelHelperInterface extends HelperInterface
{
    public static function isNullableBySwaggerConfiguration(string $targetedProperty, array $definition): bool;


    public static function getArrayEmbeddedObjectConfig(array $config): ?array;


    public static function flattenDefinitionType(string $definitionType, array $definitions, string $definitionName): array;


    public static function foundNotInheritedProperties(array $definition): array;
}
