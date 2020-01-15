<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;

interface PropertiesBuilderInterface extends BuilderInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param PropertyBuilderInterface[] $properties
     */
    public function configure(UsesBuilderInterface $usesBuilder, array $properties = []): void;

    /**
     * @param PropertyBuilderInterface $propertyBuilder
     */
    public function addProperty(PropertyBuilderInterface $propertyBuilder);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool;

    /**
     * @param PropertyBuilderInterface[] $properties
     */
    public function setProperties(array $properties): void;

    /**
     * @param string $propertyName
     *
     * @return PropertyBuilderInterface|null
     */
    public function getPropertyByName(string $propertyName): ?PropertyBuilderInterface;

    /**
     * @return PropertyBuilderInterface[]
     */
    public function getProperties(): array;
}