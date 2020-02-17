<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Helper;

interface SwaggerModelHelperInterface extends HelperInterface
{
    /**
     * @param string $targetedProperty
     * @param array $definition
     *
     * @return bool
     */
    public static function isNullableBySwaggerConfiguration(string $targetedProperty, array $definition): bool;

    /**
     * @param array $config
     *
     * @return array|null
     */
    public static function getArrayEmbeddedObjectConfig(array $config): ?array;

    /**
     * @param string $definitionType
     * @param array $definitions
     * @param string $definitionName
     *
     * @return array
     */
    public static function flattenDefinitionType(string $definitionType, array $definitions, string $definitionName): array;

    /**
     * @param array $definition
     *
     * @return array
     */
    public static function foundNotInheritedProperties(array $definition): array;
}
