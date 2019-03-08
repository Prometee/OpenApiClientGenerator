<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class ClassTraitsBuilder implements BuilderInterface
{
    /** @var string[] */
    protected $traits;

    public function __construct(array $traits = [])
    {
        $this->traits = $traits;
    }

    public function build(string $indent = null): ?string
    {
        $content = (!empty($this->traits)) ? ' use '.implode(",\n".$indent, $this->traits) : '';

        if (!empty($content)) {
            $content = "\n".$content.';'."\n";
        }

        return $content;
    }

    public function addTrait(string $name, ?string $alias = null): void
    {
        if (!$this->hasTrait($name)) {
            $this->setTrait($name, $alias);
        }
    }

    public function hasTrait(string $name): bool
    {
        return isset($this->traits[$name]);
    }

    public function setTrait(string $name, ?string $alias = null): void
    {
        $this->traits[$name] = $alias;
    }
}
