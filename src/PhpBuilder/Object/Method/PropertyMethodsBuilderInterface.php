<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

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
     * @param MethodFactoryInterface $methodFactory
     * @param string|null $indent
     *
     * @return MethodBuilderInterface[]
     */
    public function getMethods(MethodFactoryInterface $methodFactory, string $indent = null): array;

    /**
     * @param bool $writeOnly
     */
    public function setWriteOnly(bool $writeOnly): void;

    /**
     * @return bool
     */
    public function isWriteOnly(): bool;
}