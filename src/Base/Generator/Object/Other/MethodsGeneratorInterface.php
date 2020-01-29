<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodGeneratorInterface;

interface MethodsGeneratorInterface extends GeneratorInterface
{
    /**
     * @param UsesGeneratorInterface $usesBuilder
     * @param MethodGeneratorInterface[] $methods
     */
    public function configure(UsesGeneratorInterface $usesBuilder, array $methods = []): void;

    /**
     * @param MethodGeneratorInterface[] $methodBuilders
     */
    public function addMultipleMethod(array $methodBuilders): void;

    /**
     * @param MethodGeneratorInterface $methodBuilder
     */
    public function addMethod(MethodGeneratorInterface $methodBuilder): void;

    /**
     * Order methods to prioritize the one starting with "__"
     */
    public function orderMethods(): void;

    /**
     * @param string $name
     *
     * @return MethodGeneratorInterface|null
     */
    public function getMethodByName(string $name): ?MethodGeneratorInterface;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod(string $name): bool;

    /**
     * @return MethodGeneratorInterface[]
     */
    public function getMethods(): array;

    /**
     * @param MethodGeneratorInterface[] $methods
     */
    public function setMethods(array $methods): void;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     */
    public function setUsesBuilder(UsesGeneratorInterface $usesBuilder): void;

    /**
     * @return UsesGeneratorInterface
     */
    public function getUsesBuilder(): UsesGeneratorInterface;
}
