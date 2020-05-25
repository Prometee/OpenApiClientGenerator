<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Helper;

interface SwaggerOperationsHelperInterface extends HelperInterface
{
    /**
     * @param string $path
     *
     * @return string
     */
    public static function getClassPathFromPath(string $path): string;

    /**
     * @param string $path
     * @param string $operation
     * @param array $operationConfiguration
     *
     * @return string
     */
    public static function getOperationMethodName(
        string $path,
        string $operation,
        array $operationConfiguration
    ): string;

    /**
     * @param array $responseConfiguration
     *
     * @return string|null
     */
    public static function getReturnType(array $responseConfiguration): ?string;
}
