<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Helper;

interface HelperInterface
{
    /**
     * @param array|null $items
     * @return string
     */
    public static function getPhpTypeFromSwaggerArrayType(?array $items): string;

    /**
     * @param string $format
     * @return string
     */
    public static function getPhpTypeFromSwaggerStringType(?string $format): string;

    /**
     * @param string $property
     * @return string
     */
    public static function cleanPropertyName(string $property): string;

    /**
     * @param array $config
     * @return string|null
     */
    public static function getPhpTypeFromSwaggerConfiguration(array $config): ?string;

    /**
     * @param string $ref
     * @return string
     */
    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string;

    /**
     * @param string $type
     * @param array|null $items
     * @return string
     */
    public static function getPhpTypeFromSwaggerTypeAndFormat(string $type, ?array $items): string;
}
