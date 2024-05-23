<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

use Exception;

class ModelHelper extends AbstractHelper implements ModelHelperInterface
{
    public static function isNullableBySwaggerConfiguration(string $targetedProperty, array $definition): bool
    {
        if (!isset($definition['properties'])) {
            return true;
        }
        if (isset($definition['required']) && in_array($targetedProperty, $definition['required'])) {
            return false;
        }

        if (!isset($definition['properties'][$targetedProperty])) {
            return true;
        }

        return $definition['properties'][$targetedProperty]['nullable']
            ?? true
        ;
    }

    public static function getArrayEmbeddedObjectConfig(array $config): ?array
    {
        if (!isset($config['items'])) {
            return null;
        }

        if (!isset($config['items']['type'])) {
            return null;
        }

        if ('object' !== $config['items']['type']) {
            return null;
        }

        return $config['items'];
    }


    /**
     * @throws Exception
     */
    public static function flattenDefinitionType(string $definitionType, array $definitions, string $definitionName): array
    {
        if (!isset($definitions[$definitionName])) {
            throw new Exception(sprintf('Unable to found definition name "%s" !', $definitionName));
        }

        $definition = $definitions[$definitionName];

        if (isset($definition[$definitionType])) {
            return $definition[$definitionType];
        }

        if (!isset($definition['allOf'])) {
            return [];
        }

        $allOf = $definition['allOf'];
        $inheritedPropertyName = static::getPhpTypeFromSwaggerDefinitionName($allOf[0]['$ref']);

        $properties = [];
        if (isset($allOf[1][$definitionType])) {
            $properties = $allOf[1][$definitionType];
        }

        $inheritedTypes = static::flattenDefinitionType($definitionType, $definitions, $inheritedPropertyName);

        return array_merge($properties, $inheritedTypes);
    }

    public static function foundNotInheritedProperties(array $definition): array
    {
        if (isset($definition['properties'])) {
            return $definition['properties'];
        }

        if (!isset($definition['allOf'])) {
            return [];
        }

        $allOf = $definition['allOf'];
        if (!isset($allOf[1]['properties'])) {
            return [];
        }

        return $allOf[1]['properties'];
    }
}
