<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class UsesBuilder implements BuilderInterface
{
    /** @var array */
    protected $uses;

    public function __construct()
    {
        $this->uses = [];
    }

    public function build(string $indent = null): ?string
    {
        $content = '';
        foreach ($this->uses as $class=>$alias) {
            $content .= 'use '.$class;
            $content .= !empty($alias) ? ' as '.$alias : '';
            $content .= ';'."\n";
        }

        if (!empty($content)) {
            $content .= "\n";
        }

        return $content;
    }

    /**
     * @param string $class
     * @param string $alias
     */
    public function addUse(string $class, string $alias = '')
    {
        if (!$this->hasUse($class)) {
            $this->setUse($class, $alias);
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    protected function hasUse(string $class)
    {
        return isset($this->uses[$class]);
    }

    /**
     * @param string $class
     * @param string $alias
     */
    public function setUse(string $class, string $alias = '')
    {
        $this->uses[trim($class, '\\')] = $alias;
    }

    /**
     * @param string $class
     * @return string|null
     */
    public function getUseAlias(string $class): ?string
    {
        if ($this->hasUse($class)) {
            return $this->uses[$class];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getUses(): array
    {
        return $this->uses;
    }

    /**
     * @param array $uses
     */
    public function setUses(array $uses): void
    {
        $this->uses = $uses;
    }

    /**
     * @param string $class
     * @return string
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
