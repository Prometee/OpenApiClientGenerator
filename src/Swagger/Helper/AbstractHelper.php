<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Helper;

use Exception;

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
     *
     * @throws Exception
     */
    public static function getPhpTypeFromSwaggerArrayType(?array $items): string
    {
        $phpType = 'array';

        if ($items !== null) {
            $phpType = static::getPhpTypeFromSwaggerConfiguration($items);
        }

        if (null === $phpType) {
            throw new Exception(sprintf('Unable to found the corresponding php type of "%s"', print_r($items)));
        }

        return $phpType;
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
    public static function cleanStr(string $str): string
    {
        return preg_replace('#[^a-z_0-9]#i', '_', $str);
    }

    /**
     * {@inheritdoc}
     */
    public static function camelize(string $str): string
    {
        $underscored = preg_replace('#-#', '_', $str);
        $underscored = self::cleanStr($underscored);
        $underscored = trim($underscored, '_');
        $words = explode('_', $underscored);
        $words = array_map('ucfirst', $words);

        return implode('', $words);
    }
}
