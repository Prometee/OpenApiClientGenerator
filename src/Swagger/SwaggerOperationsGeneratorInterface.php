<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelperInterface;

interface SwaggerOperationsGeneratorInterface
{
    public const CLASS_SUFFIX = 'Operations';

    /**
     * @param string $folder
     * @param string $namespace
     * @param string $modelNamespace
     * @param string $indent
     */
    public function configure(string $folder, string $namespace, string $modelNamespace, string $indent = '    ');

    /**
     * @param SwaggerOperationsHelperInterface $helper
     */
    public function setHelper(SwaggerOperationsHelperInterface $helper): void;

    /**
     * @return bool
     */
    public function generate(): bool;

    /**
     * @return string[]
     */
    public function getThrowsClasses(): array;

    /**
     * @param array $paths
     */
    public function setPaths(array $paths): void;

    /**
     * @param array $json
     *
     * @return bool
     */
    public function processPaths(array $json): bool;

    /**
     * @param string|null $abstractOperationClass
     */
    public function setAbstractOperationClass(?string $abstractOperationClass): void;

    /**
     * @param string $path
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return array
     */
    public function getClassNameAndNamespaceFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): array;

    /**
     * @return SwaggerOperationsHelperInterface
     */
    public function getHelper(): SwaggerOperationsHelperInterface;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param string $path
     * @param string $operation
     * @param array $operationConfiguration
     */
    public function processOperation(ClassBuilderInterface $classBuilder, string $path, string $operation, array $operationConfiguration): void;

    /**
     * @return string|null
     */
    public function getAbstractOperationClass(): ?string;

    /**
     * @param string $path
     * @param array $operationConfigurations
     *
     * @return bool|int
     */
    public function generateClass(string $path, array $operationConfigurations);

    /**
     * @return array
     */
    public function getPaths(): array;

    /**
     * @param string $type
     *
     * @return string
     */
    public function getPhpNameFromType(string $type): string;

    /**
     * @param string[] $throwsClasses
     */
    public function setThrowsClasses(array $throwsClasses): void;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param MethodBuilderInterface $methodBuilder
     * @param array $operationParameters
     */
    public function processOperationParameters(ClassBuilderInterface $classBuilder, MethodBuilderInterface $methodBuilder, array $operationParameters): void;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param array $parameterConfiguration
     *
     * @return MethodParameterBuilderInterface|null
     */
    public function createAnOperationParameter(ClassBuilderInterface $classBuilder, array $parameterConfiguration): ?MethodParameterBuilderInterface;

    /**
     * @return bool
     */
    public function isOverwrite(): bool;

    /**
     * @param bool $overwrite
     */
    public function setOverwrite(bool $overwrite): void;
}