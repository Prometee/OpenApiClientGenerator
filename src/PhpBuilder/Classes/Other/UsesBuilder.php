<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

class UsesBuilder implements UsesBuilderInterface
{
    /** @var string[] */
    protected $uses = [];

    /**
     * {@inheritDoc}
     */
    public function configure(array $uses = []): void
    {
        $this->uses = $uses;
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
        $this->uses[trim($class, '\\')] = $alias;
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
    public function getInternalClassName(string $class): ?string
    {
        if ($this->hasUse($class)) {
            $alias = $this->getUseAlias($class);
            if ($alias !== null) {
                if (empty($alias)) {
                    $class_parts = explode('\\', $class);
                    $alias = end($class_parts);
                }

                return $alias;
            }
        }

        return null;
    }
}
