<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Property;

use Prometee\SwaggerClientBuilder\BuilderInterface;

class PropertiesBuilder implements BuilderInterface
{
    /** @var PropertyBuilderInterface[] */
    protected $properties;

    public function __construct()
    {
        $this->properties = [];
    }

    public function build(string $indent = null): ?string
    {
        $content = '';

        foreach ($this->properties as $property) {
            $content .= $property->build($indent);
        }

        return $content;
    }

    public function addProperty(PropertyBuilderInterface $propertyBuilder)
    {
        if (!$this->hasProperty($propertyBuilder->getPhpName())) {
            $this->properties[$propertyBuilder->getPhpName()] = $propertyBuilder;
        }
    }

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
