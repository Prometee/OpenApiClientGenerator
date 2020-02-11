<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

interface TraitsGeneratorInterface extends GeneratorInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param string[] $traits
     */
    public function configure(UsesGeneratorInterface $usesGenerator, array $traits = []): void;

    /**
     * @param string $class
     * @param string $alias
     */
    public function setTrait(string $class, string $alias = ''): void;

    /**
     * @param string $name
     * @param string $alias
     */
    public function addTrait(string $name, string $alias = ''): void;

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasTrait(string $class): bool;

    /**
     * @return string[]
     */
    public function getTraits(): array;

    /**
     * @param string[] $traits
     */
    public function setTraits(array $traits): void;
}