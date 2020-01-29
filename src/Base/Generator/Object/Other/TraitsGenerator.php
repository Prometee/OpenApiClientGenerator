<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Other;

class TraitsGenerator implements TraitsGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesBuilder;
    /** @var string[] */
    protected $traits = [];

    /**
     * @inheritDoc
     */
    public function configure(UsesGeneratorInterface $usesBuilder, array $traits = []): void
    {
        $this->usesBuilder = $usesBuilder;
        $this->traits = $traits;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null): ?string
    {
        $content = (!empty($this->traits)) ? ' use '.implode(",\n".$indent, $this->traits) : '';

        if (!empty($content)) {
            $content = "\n".$content.';'."\n";
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function addTrait(string $name, string $alias = ''): void
    {
        if (!$this->hasTrait($name)) {
            $this->setTrait($name, $alias);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasTrait(string $class): bool
    {
        return isset($this->traits[$class]);
    }

    /**
     * {@inheritDoc}
     */
    public function setTrait(string $class, string $alias = ''): void
    {
        $this->usesBuilder->guessUse($class, $alias);
        $this->traits[$class] = $this->usesBuilder->getInternalUseName($class);
    }
}
