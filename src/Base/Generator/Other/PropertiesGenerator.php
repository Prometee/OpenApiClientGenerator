<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\PropertiesViewInterface;

class PropertiesGenerator extends AbstractGenerator implements PropertiesGeneratorInterface
{

    /** @var UsesGeneratorInterface */
    protected $usesGenerator;
    /** @var PropertyGeneratorInterface[] */
    protected $properties = [];

    /**
     * @param PropertiesViewInterface $propertiesView
     */
    public function __construct(
        PropertiesViewInterface $propertiesView
    )
    {
        $this->setView($propertiesView);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(UsesGeneratorInterface $usesGenerator, array $properties = []): void
    {
        $this->usesGenerator = $usesGenerator;
        $this->properties = $properties;
    }

    /**
     * {@inheritDoc}
     */
    public function addProperty(PropertyGeneratorInterface $propertyGenerator)
    {
        if (!$this->hasProperty($propertyGenerator->getPhpName())) {
            $this->properties[$propertyGenerator->getPhpName()] = $propertyGenerator;
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
    public function getPropertyByName(string $propertyName): ?PropertyGeneratorInterface
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
    public function getUsesGenerator(): UsesGeneratorInterface
    {
        return $this->usesGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void
    {
        $this->usesGenerator = $usesGenerator;
    }
}
