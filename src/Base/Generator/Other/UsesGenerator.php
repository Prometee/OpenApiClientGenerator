<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\View\Other\UsesViewInterface;

class UsesGenerator extends AbstractGenerator implements UsesGeneratorInterface
{
    /** @var string[] */
    protected $uses = [];
    /** @var string[] */
    protected $internalUses = [];
    /** @var string */
    protected $namespace = '';

    /**
     * @param UsesViewInterface $usesView
     */
    public function __construct(UsesViewInterface $usesView)
    {
        $this->setView($usesView);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(string $namespace, array $uses = [], array $internalUses = []): void
    {
        $this->namespace = $namespace;
        $this->uses = $uses;
        $this->internalUses = $internalUses;
    }

    /**
     * {@inheritDoc}
     */
    public function isUsable(string $str): bool
    {
        return 1 === preg_match('#\\\\#', $str);
    }

    /**
     * {@inheritDoc}
     */
    public function cleanUse(string $use): string
    {
        $cleanedUse = rtrim($use, '][');
        $cleanedUse = trim($cleanedUse, '\\');
        return $cleanedUse;
    }

    /**
     * {@inheritDoc}
     */
    public function guessUseOrReturnType(string $use): string
    {
        if (false === $this->isUsable($use)) {
            return $use;
        }

        $isArray = 1 === preg_match('#\[\]$#', $use);
        $this->guessUse($use);
        return $this->getInternalUseName($use) . ($isArray ? '[]' : '');
    }

    /**
     * {@inheritDoc}
     */
    public function guessUse(string $use, string $alias = ''): void
    {
        if (false === $this->isUsable($use)) {
            return;
        }

        $use = $this->cleanUse($use);

        if (true === $this->hasUse($use)) {
            return;
        }

        $useParts = explode('\\', $use);
        array_pop($useParts);
        $namespace = implode('\\', $useParts);

        if ($namespace === $this->namespace) {
            $this->processInternalUseName($use, $alias);
            return;
        }

        $this->addUse($use, $alias);
    }

    /**
     * {@inheritDoc}
     */
    public function addUse(string $use, string $alias = '')
    {
        if (!$this->hasUse($use)) {
            $this->setUse($use, $alias);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasUse(string $use): bool
    {
        return isset($this->uses[$use]);
    }

    /**
     * {@inheritDoc}
     */
    public function setUse(string $use, string $alias = '')
    {
        $use = $this->cleanUse($use);
        $this->uses[$use] = $alias;
        $this->processInternalUseName($use, $alias);
    }

    /**
     * {@inheritDoc}
     */
    public function getUseAlias(string $use): ?string
    {
        $use = $this->cleanUse($use);
        if ($this->hasUse($use)) {
            return $this->uses[$use];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * {@inheritDoc}
     */
    public function setUses(array $uses): void
    {
        $this->uses = $uses;
    }

    /**
     * {@inheritDoc}
     */
    public function hasInternalUse(string $internalUseName): bool
    {
        return isset($this->internalUses[$internalUseName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getInternalUse(string $internalUseName): ?string
    {
        $internalUseName = $this->cleanUse($internalUseName);
        if ($this->hasInternalUse($internalUseName)) {
            return $this->internalUses[$internalUseName];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getInternalUseName(string $use): ?string
    {
        $use = $this->cleanUse($use);
        $internalUseName = array_search($use, $this->internalUses);

        if (false === $internalUseName) {
            return null;
        }

        return $internalUseName;
    }

    /**
     * {@inheritDoc}
     */
    public function processInternalUseName(string $use, string $internalUseName = ''): void
    {
        $use = $this->cleanUse($use);
        $existingInternalUseName = $this->getInternalUseName($use);
        if (null !== $existingInternalUseName) {
            return;
        }

        if (empty($internalUseName)) {
            $useParts = explode('\\', $use);
            $internalUseName = end($useParts);
        }

        $uniqInternalUseName = $internalUseName;
        if ($this->hasInternalUse($uniqInternalUseName)) {
            $uniqInternalUseName .= 'Alias';
        }

        $i = 1;
        while ($this->hasInternalUse($uniqInternalUseName)) {
            $uniqInternalUseName = $internalUseName . ++$i;
        }

        if ($uniqInternalUseName !== $internalUseName) {
            $this->uses[$use] = $uniqInternalUseName;
        }

        $this->internalUses[$uniqInternalUseName] = $use;
    }
}
