<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

interface GetterSetterBuilderInterface
{
    public const GETTER_PREFIX = 'get';
    public const SETTER_PREFIX = 'set';

    /**
     * @param PropertyBuilderInterface $propertyBuilder
     * @param bool $readOnly
     * @param bool $writeOnly
     */
    public function configure(
        PropertyBuilderInterface $propertyBuilder,
        bool $readOnly = false,
        bool $writeOnly = false
    ): void;

    /**
     * @return bool
     */
    public function isReadOnly(): bool;

    /**
     * @param string|null $prefix
     * @param string|null $suffix
     *
     * @return string
     */
    public function getMethodName(?string $prefix = null, ?string $suffix = null): string;

    /**
     * @param string|null $indent
     */
    public function configureSetter(string $indent = null): void;

    /**
     * @param string|null $indent
     */
    public function configureGetter(string $indent = null): void;

    /**
     * @param string|null $indent
     *
     * @return MethodBuilderInterface[]
     */
    public function getMethods(string $indent = null): array;

    /**
     * @param bool $writeOnly
     */
    public function setWriteOnly(bool $writeOnly): void;

    /**
     * @param bool $readOnly
     */
    public function setReadOnly(bool $readOnly): void;

    /**
     * @return bool
     */
    public function isWriteOnly(): bool;
}