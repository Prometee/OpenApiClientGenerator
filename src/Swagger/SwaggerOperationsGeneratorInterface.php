<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodParameterBuilderInterface;

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
     * @param string $helper
     */
    public function setHelper(string $helper): void;

    /**
     * @param bool $overwrite
     *
     * @return bool
     */
    public function generate(bool $overwrite = false): bool;

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
     * @param bool $overwrite
     *
     * @return bool
     */
    public function processPaths(array $json, bool $overwrite = false): bool;

    /**
     * @param string $abstractOperationClass
     */
    public function setAbstractOperationClass(string $abstractOperationClass): void;

    /**
     * @param string $path
     * @param string $classPrefix
     * @param string $classSuffix
     *
     * @return array
     */
    public function getClassNameAndNamespaceFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): array;

    /**
     * @return string
     */
    public function getHelper(): string;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param string $path
     * @param string $operation
     * @param array $operationConfiguration
     */
    public function processOperation(ClassBuilderInterface $classBuilder, string $path, string $operation, array $operationConfiguration): void;

    /**
     * @return string
     */
    public function getAbstractOperationClass(): string;

    /**
     * @param string $path
     * @param array $operationConfigurations
     * @param bool $overwrite
     *
     * @return bool|int
     */
    public function generateClass(string $path, array $operationConfigurations, bool $overwrite = false);

    /**
     * @return array
     */
    public function getPaths(): array;

    /**
     * @param ClassBuilderInterface $classBuilder
     * @param string $type
     *
     * @return string
     */
    public function minifyClassToUses(ClassBuilderInterface $classBuilder, string $type): string;

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
}