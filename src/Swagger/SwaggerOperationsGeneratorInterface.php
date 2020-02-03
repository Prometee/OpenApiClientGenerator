<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodParameterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerOperationsHelperInterface;

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
     * @param ClassGeneratorInterface $classBuilder
     * @param string $path
     * @param string $operation
     * @param array $operationConfiguration
     */
    public function processOperation(ClassGeneratorInterface $classBuilder, string $path, string $operation, array $operationConfiguration): void;

    /**
     * @return string|null
     */
    public function getAbstractOperationClass(): ?string;

    /**
     * @param string $path
     * @param array $operationConfigurations
     *
     * @return ClassGeneratorInterface|null
     */
    public function generateClass(string $path, array $operationConfigurations): ?ClassGeneratorInterface;

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
     * @param ClassGeneratorInterface $classBuilder
     * @param MethodGeneratorInterface $methodBuilder
     * @param array $operationParameters
     */
    public function processOperationParameters(ClassGeneratorInterface $classBuilder, MethodGeneratorInterface $methodBuilder, array $operationParameters): void;

    /**
     * @param ClassGeneratorInterface $classBuilder
     * @param array $parameterConfiguration
     *
     * @return MethodParameterGeneratorInterface|null
     */
    public function createAnOperationParameter(ClassGeneratorInterface $classBuilder, array $parameterConfiguration): ?MethodParameterGeneratorInterface;

    /**
     * @return bool
     */
    public function isOverwrite(): bool;

    /**
     * @param bool $overwrite
     */
    public function setOverwrite(bool $overwrite): void;
}