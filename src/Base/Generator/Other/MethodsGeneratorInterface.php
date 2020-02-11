<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGeneratorInterface;

interface MethodsGeneratorInterface extends GeneratorInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param MethodGeneratorInterface[] $methods
     */
    public function configure(UsesGeneratorInterface $usesGenerator, array $methods = []): void;

    /**
     * @param MethodGeneratorInterface[] $methodGenerators
     */
    public function addMultipleMethod(array $methodGenerators): void;

    /**
     * @param MethodGeneratorInterface $methodGenerator
     */
    public function addMethod(MethodGeneratorInterface $methodGenerator): void;

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
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void;

    /**
     * @return UsesGeneratorInterface
     */
    public function getUsesGenerator(): UsesGeneratorInterface;
}
