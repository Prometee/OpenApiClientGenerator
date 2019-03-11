<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Helper;

interface SwaggerModelHelperInterface extends HelperInterface
{
    /**
     * @param string $targetedProperty
     * @param array $definition
     *
     * @return bool
     */
    public static function isNullableBySwaggerConfiguration(string $targetedProperty, array $definition): bool;
}
