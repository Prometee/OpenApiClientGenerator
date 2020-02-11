<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

interface UsesGeneratorInterface extends GeneratorInterface
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
    public function isUsable(string $str): bool;

    /**
     * @param string $use
     *
     * @return string
     */
    public function guessUseOrReturnType(string $use): string;

    /**
     * @param string $use
     * @param string $alias
     */
    public function guessUse(string $use, string $alias = ''): void;

    /**
     * @param string $internalUseName
     *
     * @return bool
     */
    public function hasInternalUse(string $internalUseName): bool;

    /**
     * @param string $internalUseName
     *
     * @return string|null
     */
    public function getInternalUse(string $internalUseName): ?string;

    /**
     * @param string $use
     *
     * @return string|null
     */
    public function getInternalUseName(string $use): ?string;

    /**
     * @param string $use
     * @param string $internalUseName
     */
    public function processInternalUseName(string $use, string $internalUseName = '');

    /**
     * @param string $use
     *
     * @return string
     */
    public function cleanUse(string $use): string;

    /**
     * @param string $use
     *
     * @return string|null
     */
    public function getUseAlias(string $use): ?string;

    /**
     * @param string $use
     * @param string $alias
     */
    public function setUse(string $use, string $alias = '');

    /**
     * @param string $use
     * @param string $alias
     */
    public function addUse(string $use, string $alias = '');

    /**
     * @param string $use
     *
     * @return bool
     */
    public function hasUse(string $use): bool;

    /**
     * @return string[]
     */
    public function getUses(): array;

    /**
     * @param array $uses
     */
    public function setUses(array $uses): void;
}