<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

use RuntimeException;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * @throws RuntimeException
     */
    public static function getPhpTypeFromSwaggerConfiguration(array $config): ?string
    {
        $type = $config['type'] ?? $config['schema']['type'] ?? null;
        if (null !== $type) {
            $items = $config['items'] ?? null;
            $format = $config['format'] ?? null;
            return self::getPhpTypeFromSwaggerConfigurationType($type, $items, $format);
        }

        if (isset($config['$ref'])) {
            return static::getPhpTypeFromSwaggerDefinitionName($config['$ref']);
        }

        if ($config['schema'] && isset($config['schema']['$ref'])) {
            return static::getPhpTypeFromSwaggerDefinitionName($config['schema']['$ref']);
        }

        return null;
    }

    /**
     * @throws RuntimeException
     */
    public static function getPhpTypeFromSwaggerConfigurationType(string $type, ?array $items = null, ?string $format = null): ?string
    {
        if ('string' === $type) {
            return static::getPhpTypeFromSwaggerStringType($format);
        }

        if ('array' === $type) {
            return static::getPhpTypeFromSwaggerArrayType($items) . '[]';
        }

        if ('object' === $type) {
            return '\\stdClass';
        }

        if ('boolean' === $type) {
            return 'bool';
        }

        if ('integer' === $type) {
            return 'int';
        }

        if ('number' === $type) {
            return 'float';
        }

        return null;
    }

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
     * @throws RuntimeException
     */
    public static function getPhpTypeFromSwaggerArrayType(?array $items): string
    {
        $phpType = 'array';

        if ($items !== null) {
            $phpType = static::getPhpTypeFromSwaggerConfiguration($items);
        }

        if (null === $phpType) {
            throw new RuntimeException(sprintf('Unable to found the corresponding php type of "%s"', print_r($items)));
        }

        return $phpType;
    }

    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string
    {
        /** @var string $type */
        $type = preg_replace('$#/definitions/$', '', $ref);
        /** @var string $type */
        $type = preg_replace('$#/components/schemas/$', '', $type);
        return $type;
    }

    public static function getPhpTypeFromSwaggerTypeAndFormat(string $type, ?array $items): string
    {
        return match ($type) {
            'boolean' => 'bool',
            'integer' => 'int',
            'number' => 'float',
            'array' => static::getPhpTypeFromSwaggerDefinitionName($items['$ref'] ?? '') . '[]',
            default => $type,
        };
    }

    public static function cleanStr(string $str): string
    {
        /** @var string $cleanedStr */
        $cleanedStr = preg_replace('#[^a-z_0-9/]#i', '_', $str);
        return $cleanedStr;
    }

    public static function camelize(string $str): string
    {
        $underscored = str_replace("-", '_', $str);
        $underscored = self::cleanStr($underscored);
        $underscored = trim($underscored, '_');
        $words = explode('_', $underscored);
        $words = array_map('ucfirst', $words);

        return implode('', $words);
    }
}
