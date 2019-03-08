<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpDocAwareBuilderInterface;

interface MethodBuilderInterface extends BuilderInterface, PhpDocAwareBuilderInterface
{
    public const SCOPE_PUBLIC = 'public';
    public const SCOPE_PROTECTED = 'protected';
    public const SCOPE_PRIVATE = 'private';

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return array|null
     */
    public function getReturnTypes(): ?array;

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
     * @param string|null $returnType
     */
    public function setReturnType(?string $returnType): void;

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
     * @return string|null
     */
    public function getReturnType(): ?string;

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
}
