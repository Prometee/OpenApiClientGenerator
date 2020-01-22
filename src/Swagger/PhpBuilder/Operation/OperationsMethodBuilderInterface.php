<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Operation;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilderInterface;

interface OperationsMethodBuilderInterface extends MethodBuilderInterface
{
    /**
     * @return string
     */
    public function getMinifiedReturnType(): string;

    /**
     * @param string $path
     * @param string $operation
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addMethodBodyFromSwaggerConfiguration(
        string $path,
        string $operation,
        array $operationParameters,
        string $indent = null
    ): void;

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addGetOperationLines(string $path, array $operationParameters, string $indent = null): void;

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addPostOperationLines(string $path, array $operationParameters, string $indent = null): void;

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addPutOperationLines(string $path, array $operationParameters, string $indent = null): void;

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addDeleteOperationLines(string $path, array $operationParameters, string $indent = null): void;

    /**
     * @param array $operationParameters
     *
     * @return array
     */
    public function buildPathAndQueryParams(array $operationParameters): array;

    /**
     * @param array $operationParameters
     *
     * @return string
     */
    public function buildQueryParam(array $operationParameters): string;
}