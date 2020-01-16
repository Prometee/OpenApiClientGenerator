<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;

interface TraitsBuilderInterface extends BuilderInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param string[] $traits
     */
    public function configure(UsesBuilderInterface $usesBuilder, array $traits = []): void;

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
}