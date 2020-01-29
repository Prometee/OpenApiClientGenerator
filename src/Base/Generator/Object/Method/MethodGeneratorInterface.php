<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocAwareGeneratorInterface;

interface MethodGeneratorInterface extends GeneratorInterface, PhpDocAwareGeneratorInterface
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
     * @param string $indent
     *
     * @return string
     */
    public function buildMethodParameters(string $indent): string;

    /**
     * @param string $indent
     *
     * @return string
     */
    public function buildReturnType(string $indent): string;

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
     * @param MethodParameterGeneratorInterface[] $parameters
     */
    public function setParameters(array $parameters): void;

    /**
     * @param string $line
     */
    public function addLine(string $line): void;

    /**
     * {@inheritdoc}
     */
    public function hasParameter(MethodParameterGeneratorInterface $methodParameterBuilder): bool;

    /**
     * @param bool $static
     */
    public function setStatic(bool $static): void;

    /**
     * @return MethodParameterGeneratorInterface[]
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
    public function setParameter(MethodParameterGeneratorInterface $methodParameterBuilder): void;

    public function configurePhpDocBuilder(): void;

    /**
     * {@inheritdoc}
     */
    public function addParameter(MethodParameterGeneratorInterface $methodParameterBuilder): void;

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
     * @return UsesGeneratorInterface
     */
    public function getUsesBuilder(): UsesGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     */
    public function setUsesBuilder(UsesGeneratorInterface $usesBuilder): void;

    /**
     * @return MethodParameterGeneratorInterface
     */
    public function getMethodParameterBuilderSkel(): MethodParameterGeneratorInterface;

    /**
     * @param MethodParameterGeneratorInterface $methodParameterBuilderSkel
     */
    public function setMethodParameterBuilderSkel(MethodParameterGeneratorInterface $methodParameterBuilderSkel): void;
}
