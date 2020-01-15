<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;

interface PropertyMethodsBuilderInterface
{
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
     * @param bool $readOnly
     */
    public function setReadOnly(bool $readOnly): void;

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
     * @return bool
     */
    public function isWriteOnly(): bool;
}