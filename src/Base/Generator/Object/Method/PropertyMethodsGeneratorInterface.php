<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;

interface PropertyMethodsGeneratorInterface
{
    /**
     * @param PropertyGeneratorInterface $propertyGenerator
     * @param bool $readOnly
     * @param bool $writeOnly
     */
    public function configure(
        PropertyGeneratorInterface $propertyGenerator,
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
     * @return MethodGeneratorInterface[]
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