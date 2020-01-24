<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilder;

class ModelPropertyBuilder extends PropertyBuilder implements ModelPropertyBuilderInterface
{
    /** @var bool */
    protected $required = false;
    /** @var bool */
    protected $inherited = false;

    public function build(string $indent = null): ?string
    {
        if ($this->isInherited()) {
            return null;
        }

        return parent::build($indent);
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