<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;

interface GetterSetterGeneratorInterface
{
    public const GETTER_PREFIX = 'get';
    public const SETTER_PREFIX = 'set';

    /**
     * @param PropertyGeneratorInterface $propertyBuilder
     * @param bool $readOnly
     * @param bool $writeOnly
     */
    public function configure(
        PropertyGeneratorInterface $propertyBuilder,
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
     * @return MethodGeneratorInterface[]
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