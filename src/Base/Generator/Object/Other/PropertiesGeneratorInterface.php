<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;

interface PropertiesGeneratorInterface extends GeneratorInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param PropertyGeneratorInterface[] $properties
     */
    public function configure(UsesGeneratorInterface $usesGenerator, array $properties = []): void;

    /**
     * @param PropertyGeneratorInterface $propertyGenerator
     */
    public function addProperty(PropertyGeneratorInterface $propertyGenerator);

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
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void;

    /**
     * @return UsesGeneratorInterface
     */
    public function getUsesGenerator(): UsesGeneratorInterface;
}