<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

interface HelperInterface
{
    public static function getPhpTypeFromSwaggerArrayType(?array $items): string;

    /**
     * @param string $format
     */
    public static function getPhpTypeFromSwaggerStringType(?string $format): string;

    
    public static function cleanStr(string $str): string;

    
    public static function getPhpTypeFromSwaggerConfiguration(array $config): ?string;

    
    public static function getPhpTypeFromSwaggerConfigurationType(
        string $type,
        ?array $items = null,
        ?string $format = null
    ): ?string;

    
    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string;

    
    public static function getPhpTypeFromSwaggerTypeAndFormat(string $type, ?array $items): string;

    
    public static function camelize(string $str): string;
}
