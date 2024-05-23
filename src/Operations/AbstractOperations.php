<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Operations;

abstract class AbstractOperations implements OperationsInterface
{
    public function execGetOperation(string $operation, string $responseType, array $pathParams = [], array $queryParams = []): mixed
    {
        return null;
    }

    public function execPostOperation(string $operation, mixed $data, string $responseType, array $pathParams = [], array $queryParams = []): mixed
    {
        return null;
    }

    public function execPutOperation(string $operation, mixed $data, string $responseType, array $pathParams = [], array $queryParams = []): mixed
    {
        return null;
    }

    public function execDeleteOperation(string $operation, ?string $responseType = null, array $pathParams = [], array $queryParams = []): mixed
    {
        return null;
    }
}
