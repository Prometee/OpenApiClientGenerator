<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;

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
     * @param MethodGeneratorFactoryInterface $methodFactory
     * @param string|null $indent
     *
     * @return MethodGeneratorInterface[]
     */
    public function getMethods(MethodGeneratorFactoryInterface $methodFactory, string $indent = null): array;

    /**
     * @param bool $writeOnly
     */
    public function setWriteOnly(bool $writeOnly): void;

    /**
     * @return bool
     */
    public function isWriteOnly(): bool;
}