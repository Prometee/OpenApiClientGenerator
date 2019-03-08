<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Helper;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerConfiguration(array $config): ?string
    {
        if (isset($config['type'])) {
            $type = $config['type'];
            $items = isset($config['items']) ? $config['items'] : null;
            $format = isset($config['format']) ? $config['format'] : null;
            switch ($type) {
                case 'boolean':
                    return 'bool';
                case 'integer':
                    return 'int';
                case 'number':
                    return 'float';
                case 'array':
                    return static::getPhpTypeFromSwaggerArrayType($items).'[]';
                case 'object':
                    return '\\stdClass';
                case 'string':
                    return static::getPhpTypeFromSwaggerStringType($format);
            }
        } else if (isset($config['$ref'])) {
            return static::getPhpTypeFromSwaggerDefinitionName($config['$ref']);
        } else if ($config['schema'] && isset($config['schema']['$ref'])) {
            return static::getPhpTypeFromSwaggerDefinitionName($config['schema']['$ref']);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerStringType(?string $format): string
    {
        switch ($format) {
            case 'date':
                return '\\DateTimeInterface';
            case 'date-time':
                return '\\DateTimeInterface';
        }

        return 'string';
    }

    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerArrayType(?array $items): string
    {
        if ($items !== null) {
            return static::getPhpTypeFromSwaggerConfiguration($items);
        }

        return 'array';
    }

    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string
    {
        return preg_replace('$#/definitions/$', '', $ref);
    }

    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerTypeAndFormat(string $type, ?array $items): string
    {
        switch ($type) {
            case 'boolean':
                return 'bool';
            case 'integer':
                return 'int';
            case 'number':
                return 'float';
            case 'array':
                return static::getPhpTypeFromSwaggerDefinitionName($items['$ref']) . '[]';
            default:
                return $type;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function cleanPropertyName(string $property): string
    {
        return preg_replace('#[^a-z_0-9]#i', '_', $property);
    }
}
