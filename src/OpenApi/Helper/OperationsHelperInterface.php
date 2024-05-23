<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

interface OperationsHelperInterface extends HelperInterface
{
    public static function getClassPathFromPath(string $path): string;

    public static function getOperationMethodName(
        string $path,
        string $operation,
        array $operationConfiguration
    ): string;

    public static function getReturnTypes(
        array $responseConfiguration,
        bool $onlyOkCodes = true,
    ): array;
}
