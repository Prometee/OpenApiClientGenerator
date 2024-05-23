<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Operation;

interface OperationsMethodGeneratorInterface
{
    public function getMinifiedReturnType(string $returnType): string;

    /**
     * @return string[]
     */

    public function createMethodBodyFromSwaggerConfiguration(
        string $path,
        string $operation,
        array $parametersConfiguration,
        string $returnType,
        ?string $indent = "\t"
    ): array;

    /**
     * @param string $returnType
     * @return string[]
     */
    public function createOperationTypeLines(
        string $path,
        string $operation,
        array $parametersConfiguration,
        string $returnType,
        ?string $bodyParam,
        ?string $indent = "\t"
    ): array;

    /**
     * @return string[]
     */
    public function buildParamsType(string $type, array $parametersConfiguration, string $format): array;


    public function buildBodyParam(): string;
}
