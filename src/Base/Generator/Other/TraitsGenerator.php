<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\View\Other\TraitsViewInterface;

class TraitsGenerator extends AbstractGenerator implements TraitsGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesGenerator;
    /** @var string[] */
    protected $traits = [];

    /**
     * @param TraitsViewInterface $traitsView
     */
    public function __construct(
        TraitsViewInterface $traitsView
    )
    {
        $this->setView($traitsView);
    }

    /**
     * @inheritDoc
     */
    public function configure(UsesGeneratorInterface $usesGenerator, array $traits = []): void
    {
        $this->usesGenerator = $usesGenerator;
        $this->traits = $traits;
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
        $this->usesGenerator->guessUse($class, $alias);
        $this->traits[$class] = $this->usesGenerator->getInternalUseName($class);
    }

    /**
     * {@inheritDoc}
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraits(array $traits): void
    {
        $this->traits = $traits;
    }
}
