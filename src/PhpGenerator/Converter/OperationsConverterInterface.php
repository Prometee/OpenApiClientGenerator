<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Converter;

interface OperationsConverterInterface
{
    public const CLASS_SUFFIX = 'Operations';

    public function convert(array $paths): array;


    public function setPaths(array $paths): void;


    public function processPaths(array $json): bool;


    public function getClassNameAndNamespaceFromPath(
        string $path,
        string $classPrefix = '',
        string $classSuffix = ''
    ): array;

    public function processOperations(string $path, array $pathConfig): array;

    public function processOperation(
        string $path,
        string $operation,
        array $operationConfiguration
    ): array;


    public function generateClass(string $path, array $pathConfig): array;

    public function getPaths(): array;


    public function getPhpNameFromType(string $type): string;

    public function processOperationParameters(array $operationParameters): array;

    public function processOperationRequestBody(array $operationRequestBody): array;


    public function createAnOperationParameter(array $parameterConfiguration): array;


    public function buildValueForOperationParameter(
        array $parameterConfiguration,
        ?string $type
    ): ?string;

    public function getThrowsClasses(): array;
    public function setThrowsClasses(array $throwsClasses): void;

    public function getAbstractOperationsClass(): ?string;

    public function setAbstractOperationsClass(?string $abstractOperationsClass): void;
}
