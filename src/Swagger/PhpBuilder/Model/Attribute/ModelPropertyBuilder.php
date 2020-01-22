<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilder;

class ModelPropertyBuilder extends PropertyBuilder implements ModelPropertyBuilderInterface
{
    /** @var bool */
    protected $required = false;

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
}