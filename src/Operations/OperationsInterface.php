<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Operations;

interface OperationsInterface
{
    public function execGetOperation(string $operation, string $responseType, array $pathParams = [], array $queryParams = []): mixed;

    public function execPostOperation(string $operation, mixed $data, string $responseType, array $pathParams = [], array $queryParams = []): mixed;

    public function execPutOperation(string $operation, mixed $data, string $responseType, array $pathParams = [], array $queryParams = []): mixed;

    public function execDeleteOperation(string $operation, ?string $responseType = null, array $pathParams = [], array $queryParams = []): mixed;
}
