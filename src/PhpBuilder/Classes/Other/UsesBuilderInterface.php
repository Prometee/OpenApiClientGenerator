<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;

interface UsesBuilderInterface extends BuilderInterface
{
    /**
     * @param string $namespace
     * @param string[] $uses
     * @param string[] $internalUses
     */
    public function configure(string $namespace, array $uses = [], array $internalUses = []): void;

    /**
     * @param string $str
     *
     * @return bool
     */
    public function isAClass(string $str): bool;

    /**
     * @param string $class
     * @param string $alias
     */
    public function guessUse(string $class, string $alias = ''): void;

    /**
     * @param string $internalClassName
     *
     * @return bool
     */
    public function hasInternalUse(string $internalClassName): bool;

    /**
     * @param string $internalClassName
     *
     * @return string|null
     */
    public function getInternalUseClass(string $internalClassName): ?string;

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function getInternalUseClassName(string $class): ?string;

    /**
     * @param string $class
     * @param string $internalClassName
     */
    public function processInternalUseClassName(string $class, string $internalClassName = '');

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