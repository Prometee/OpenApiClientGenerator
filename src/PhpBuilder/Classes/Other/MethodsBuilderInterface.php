<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;

interface MethodsBuilderInterface extends BuilderInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param MethodBuilderInterface[] $methods
     */
    public function configure(UsesBuilderInterface $usesBuilder, array $methods = []): void;

    /**
     * @param MethodBuilderInterface[] $methodBuilders
     */
    public function addMultipleMethod(array $methodBuilders): void;

    /**
     * @param MethodBuilderInterface $methodBuilder
     */
    public function addMethod(MethodBuilderInterface $methodBuilder): void;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod(string $name): bool;

    /**
     * @return MethodBuilderInterface[]
     */
    public function getMethods(): array;

    /**
     * @param MethodBuilderInterface[] $methods
     */
    public function setMethods(array $methods): void;
}
