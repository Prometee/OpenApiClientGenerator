<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Exception;

class UsesBuilder implements UsesBuilderInterface
{
    /** @var string[] */
    protected $uses = [];
    /** @var string[] */
    protected $internalUses = [];
    /** @var string */
    protected $namespace = '';

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
    public function isAClass(string $str): bool
    {
        return 1 === preg_match('#\\\\#', $str);
    }

    /**
     * {@inheritDoc}
     */
    public function guessUse(string $class, string $alias = ''): void
    {
        if (true === $this->hasUse($class)) {
            return;
        }

        $classParts = explode('\\', $class);
        $className = end($classParts);
        array_pop($classParts);
        $namespace = implode('\\', $classParts);

        if ($this->getInternalUseClass($className) === $namespace) {
            return;
        }

        if ($namespace === $this->namespace) {
            $this->processInternalUseClassName($class, $alias);
            return;
        }

        $this->addUse($class, $alias);
    }

    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = '';
        foreach ($this->uses as $class => $alias) {
            $content .= 'use ' . $class;
            $content .= !empty($alias) ? ' as ' . $alias : '';
            $content .= ';' . "\n";
        }

        if (!empty($content)) {
            $content .= "\n";
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function addUse(string $class, string $alias = '')
    {
        if (!$this->hasUse($class)) {
            $this->setUse($class, $alias);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasUse(string $class): bool
    {
        return isset($this->uses[$class]);
    }

    /**
     * {@inheritDoc}
     */
    public function setUse(string $class, string $alias = '')
    {
        $class = trim($class, '\\');
        $this->uses[$class] = $alias;
        $this->processInternalUseClassName($class, $alias);
    }

    /**
     * {@inheritDoc}
     */
    public function getUseAlias(string $class): ?string
    {
        if ($this->hasUse($class)) {
            return $this->uses[$class];
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
    public function hasInternalUse(string $internalClassName): bool
    {
        return isset($this->internalUses[$internalClassName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getInternalUseClass(string $internalClassName): ?string
    {
        if ($this->hasInternalUse($internalClassName)) {
            return $this->internalUses[$internalClassName];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getInternalUseClassName(string $class): ?string
    {
        $internalClassName = array_search($class, $this->internalUses);

        if (false === $internalClassName) {
            return null;
        }

        return $internalClassName;
    }

    /**
     * {@inheritDoc}
     */
    public function processInternalUseClassName(string $class, string $internalClassName = ''): void
    {
        $existingInternalClassName = $this->getInternalUseClassName($class);
        if (null !== $existingInternalClassName) {
            return;
        }

        if (empty($internalClassName)) {
            $classParts = explode('\\', $class);
            $internalClassName = end($classParts);
        }

        $uniqInternalClassName = $internalClassName;
        if ($this->hasInternalUse($uniqInternalClassName)) {
            $uniqInternalClassName .= 'Alias';
        }

        $i = 1;
        while ($this->hasInternalUse($uniqInternalClassName)) {
            $uniqInternalClassName = $internalClassName . ++$i;
        }

        if ($uniqInternalClassName !== $internalClassName) {
            $this->uses[$class] = $uniqInternalClassName;
        }

        $this->internalUses[$uniqInternalClassName] = $class;
    }
}
