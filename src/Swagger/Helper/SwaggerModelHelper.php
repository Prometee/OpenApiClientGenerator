<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Helper;

class SwaggerModelHelper extends AbstractHelper implements SwaggerModelHelperInterface
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
}
