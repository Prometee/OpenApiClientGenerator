<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Operation;

use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGeneratorInterface;

interface OperationsMethodGeneratorInterface extends MethodGeneratorInterface
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
     * @param string $operation
     * @param string $path
     * @param array $operationParameters
     * @param string|null $bodyParam
     * @param string|null $indent
     */
    public function addOperationTypeLines(string $operation, string $path, array $operationParameters, string $bodyParam = null, string $indent = null): void;

    /**
     * @param string $type
     * @param array $operationParameters
     * @param string|null $lineBreak
     * @param string|null $indent
     *
     * @return string
     */
    public function generateParamsType(string $type, array $operationParameters, string $lineBreak = null, string $indent = null): string;

    /**
     * @param string $type
     * @param array $operationParameters
     * @param string $format
     *
     * @return string[]
     */
    public function buildParamsType(string $type, array $operationParameters, string $format): array;

    /**
     * @param array $operationParameters
     *
     * @return string
     */
    public function buildBodyParam(array $operationParameters): string;
}