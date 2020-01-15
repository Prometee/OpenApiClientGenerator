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
            $items = $config['items'] ?? null;
            $format = $config['format'] ?? null;
            switch ($type) {
                case 'boolean':
                    return 'bool';
                case 'integer':
                    return 'int';
                case 'number':
                    return 'float';
                case 'array':
                    return static::getPhpTypeFromSwaggerArrayType($items) . '[]';
                case 'object':
                    return '\\stdClass';
                case 'string':
                    return static::getPhpTypeFromSwaggerStringType($format);
            }
        } elseif (isset($config['$ref'])) {
            return static::getPhpTypeFromSwaggerDefinitionName($config['$ref']);
        } elseif ($config['schema'] && isset($config['schema']['$ref'])) {
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
            case 'date-time':
            case 'date':
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

    /**
     * {@inheritdoc}
     */
    public static function getClassNameFromDefinitionName(string $definitionName): string
    {
        $className = preg_replace('#-#', '_', $definitionName);
        $className = trim($className, '_');
        $words = explode('_', $className);
        $words = array_map('ucfirst', $words);
        $className = implode('', $words);

        return $className;
    }
}
