<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute;

use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGenerator;

class ModelPropertyGenerator extends PropertyGenerator implements ModelPropertyGeneratorInterface
{
    /** @var bool */
    protected $required = false;
    /** @var bool */
    protected $inherited = false;

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null, string $eol = null): ?string
    {
        if ($this->isInherited()) {
            return null;
        }

        return parent::generate($indent);
    }

    /**
     * {@inheritDoc}
     */
    public function addType(string $type): void
    {
        if (false === $this->isInherited()) {
            $type = $this->usesGenerator->guessUseOrReturnType($type);
        }

        if (false === $this->hasType($type)) {
            $this->types[] = $type;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * {@inheritDoc}
     */
    public function isInherited(): bool
    {
        return $this->inherited;
    }

    /**
     * {@inheritDoc}
     */
    public function setInherited(bool $inherited): void
    {
        $this->inherited = $inherited;
    }
}