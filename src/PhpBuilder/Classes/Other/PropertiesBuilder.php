<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;

class PropertiesBuilder implements PropertiesBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;
    /** @var PropertyBuilderInterface[] */
    protected $properties = [];

    /**
     * {@inheritDoc}
     */
    public function configure(UsesBuilderInterface $usesBuilder, array $properties = []): void
    {
        $this->usesBuilder = $usesBuilder;
        $this->properties = $properties;
    }

    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = '';

        foreach ($this->properties as $property) {
            $content .= $property->build($indent);
        }

        return $content;
    }

    /**
     * @param PropertyBuilderInterface $propertyBuilder
     */
    public function addProperty(PropertyBuilderInterface $propertyBuilder)
    {
        if (!$this->hasProperty($propertyBuilder->getPhpName())) {
            $this->properties[$propertyBuilder->getPhpName()] = $propertyBuilder;
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * @return PropertyBuilderInterface[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param PropertyBuilderInterface[] $properties
     */
    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * @param string $propertyName
     *
     * @return PropertyBuilderInterface|null
     */
    public function getPropertyByName(string $propertyName): ?PropertyBuilderInterface
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $propertyName) {
                return $property;
            }
        }

        return null;
    }
}
