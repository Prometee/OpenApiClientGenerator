<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;

interface PropertiesGeneratorInterface extends GeneratorInterface
{
    /**
     * @param UsesGeneratorInterface $usesBuilder
     * @param PropertyGeneratorInterface[] $properties
     */
    public function configure(UsesGeneratorInterface $usesBuilder, array $properties = []): void;

    /**
     * @param PropertyGeneratorInterface $propertyBuilder
     */
    public function addProperty(PropertyGeneratorInterface $propertyBuilder);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool;

    /**
     * @param PropertyGeneratorInterface[] $properties
     */
    public function setProperties(array $properties): void;

    /**
     * @param string $propertyName
     *
     * @return PropertyGeneratorInterface|null
     */
    public function getPropertyByName(string $propertyName): ?PropertyGeneratorInterface;

    /**
     * @return PropertyGeneratorInterface[]
     */
    public function getProperties(): array;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     */
    public function setUsesBuilder(UsesGeneratorInterface $usesBuilder): void;

    /**
     * @return UsesGeneratorInterface
     */
    public function getUsesBuilder(): UsesGeneratorInterface;
}