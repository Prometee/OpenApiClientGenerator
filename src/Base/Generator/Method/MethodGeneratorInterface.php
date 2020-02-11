<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
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
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getReturnType(): string;

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
    public function hasParameter(MethodParameterGeneratorInterface $methodParameterGenerator): bool;

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
    public function setParameter(MethodParameterGeneratorInterface $methodParameterGenerator): void;

    public function configurePhpDocGenerator(): void;

    /**
     * {@inheritdoc}
     */
    public function addParameter(MethodParameterGeneratorInterface $methodParameterGenerator): void;

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
    public function getUsesGenerator(): UsesGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void;

    /**
     * @return MethodParameterGeneratorInterface
     */
    public function getMethodParameterGeneratorSkel(): MethodParameterGeneratorInterface;

    /**
     * @param MethodParameterGeneratorInterface $methodParameterGeneratorSkel
     */
    public function setMethodParameterGeneratorSkel(MethodParameterGeneratorInterface $methodParameterGeneratorSkel): void;
}
