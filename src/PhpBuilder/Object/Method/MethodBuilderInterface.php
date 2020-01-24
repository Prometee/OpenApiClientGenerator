<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocAwareBuilderInterface;

interface MethodBuilderInterface extends BuilderInterface, PhpDocAwareBuilderInterface
{
    public const SCOPE_PUBLIC = 'public';
    public const SCOPE_PROTECTED = 'protected';
    public const SCOPE_PRIVATE = 'private';

    /**
     * @param string $scope
     * @param string $name
     * @param string[] $returnTypes
     * @param bool $static
     * @param string $description
     */
    public function configure(
        string $scope,
        string $name,
        array $returnTypes = [],
        bool $static = false,
        string $description = ''
    );

    /**
     * @param string|null $indent
     *
     * @return string
     */
    public function buildMethodBody(string $indent = null): string;

    /**
     * @param string|null $indent
     *
     * @return string
     */
    public function buildMethodSignature(string $indent = null): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array
     */
    public function getReturnTypes(): array;

    /**
     * @return string
     */
    public function getPhpReturnType(): ?string;

    /**
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void;

    /**
     * @param string $line
     */
    public function addLine(string $line): void;

    /**
     * {@inheritdoc}
     */
    public function hasParameter(MethodParameterBuilder $methodParameterBuilder): bool;

    /**
     * @param bool $static
     */
    public function setStatic(bool $static): void;

    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param string $scope
     */
    public function setScope(string $scope): void;

    /**
     * @param array $lines
     */
    public function setLines(array $lines): void;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @param string[] $returnTypes
     */
    public function setReturnTypes(array $returnTypes): void;

    /**
     * @param string $returnType
     */
    public function addReturnType(string $returnType): void;

    /**
     * @param string $returnType
     *
     * @return bool
     */
    public function hasReturnType(string $returnType): bool;

    /**
     * {@inheritdoc}
     */
    public function setParameter(MethodParameterBuilder $methodParameterBuilder): void;

    public function configurePhpDocBuilder(): void;

    /**
     * {@inheritdoc}
     */
    public function addParameter(MethodParameterBuilder $methodParameterBuilder): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getScope(): string;

    /**
     * @return array
     */
    public function getLines(): array;

    /**
     * @return UsesBuilderInterface
     */
    public function getUsesBuilder(): UsesBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     */
    public function setUsesBuilder(UsesBuilderInterface $usesBuilder): void;

    /**
     * @return MethodParameterBuilderInterface
     */
    public function getMethodParameterBuilderSkel(): MethodParameterBuilderInterface;

    /**
     * @param MethodParameterBuilderInterface $methodParameterBuilderSkel
     */
    public function setMethodParameterBuilderSkel(MethodParameterBuilderInterface $methodParameterBuilderSkel): void;
}
