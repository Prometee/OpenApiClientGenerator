<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

interface ArrayGetterSetterGeneratorInterface extends GetterSetterGeneratorInterface
{
    public const ADD_SETTER_PREFIX = 'add';
    public const HAS_GETTER_PREFIX = 'has';
    public const REMOVE_SETTER_PREFIX = 'remove';

    /**
     * @param string|null $indent
     */
    public function configureSetter(string $indent = null): void;

    /**
     * @param string|null $indent
     */
    public function configureAddSetter(string $indent = null): void;

    /**
     * @return string|null
     */
    public function getSingleTypeName(): ?string;

    /**
     * @param string|null $indent
     */
    public function configureRemoveSetter(string $indent = null): void;

    /**
     * @param string|null $indent
     */
    public function configureGetter(string $indent = null): void;

    /**
     * @param string|null $indent
     *
     * @return MethodGeneratorInterface[]
     */
    public function getMethods(string $indent = null): array;

    /**
     * @param string|null $indent
     */
    public function configureHasGetter(string $indent = null): void;

    /**
     * @return string
     */
    public function getSingleName(): string;

    /**
     * @param string|null $prefix
     * @param string|null $suffix
     *
     * @return string
     */
    public function getSingleMethodName(?string $prefix = null, ?string $suffix = null): string;
}