<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

class PropertiesBuilder implements PropertiesBuilderInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;

    /** @var UsesBuilderInterface */
    protected $usesBuilder;
    /** @var PropertyBuilderInterface[] */
    protected $properties = [];

    /**
     * @param ClassFactoryInterface $classFactory
     */
    public function __construct(ClassFactoryInterface $classFactory)
    {
        $this->classFactory = $classFactory;
    }

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
     * {@inheritDoc}
     */
    public function addProperty(PropertyBuilderInterface $propertyBuilder)
    {
        if (!$this->hasProperty($propertyBuilder->getPhpName())) {
            $this->properties[$propertyBuilder->getPhpName()] = $propertyBuilder;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * {@inheritDoc}
     */
    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     */
    public function getUsesBuilder(): UsesBuilderInterface
    {
        return $this->usesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesBuilder(UsesBuilderInterface $usesBuilder): void
    {
        $this->usesBuilder = $usesBuilder;
    }
}
