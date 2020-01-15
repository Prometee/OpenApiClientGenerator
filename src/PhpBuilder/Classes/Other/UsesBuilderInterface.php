<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;

interface UsesBuilderInterface extends BuilderInterface
{
    /**
     * @param string[] $uses
     */
    public function configure(array $uses = []): void;

    /**
     * @param string $class
     *
     * @return string
     */
    public function getInternalClassName(string $class): ?string;

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function getUseAlias(string $class): ?string;

    /**
     * @param string $class
     * @param string $alias
     */
    public function setUse(string $class, string $alias = '');

    /**
     * @param string $class
     * @param string $alias
     */
    public function addUse(string $class, string $alias = '');

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasUse(string $class): bool;

    /**
     * @return string[]
     */
    public function getUses(): array;

    /**
     * @param array $uses
     */
    public function setUses(array $uses): void;
}