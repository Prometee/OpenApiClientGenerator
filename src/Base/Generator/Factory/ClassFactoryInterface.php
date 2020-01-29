<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

interface ClassFactoryInterface
{
    /**
     * @return ClassGeneratorInterface
     */
    public function createClassBuilder(): ClassGeneratorInterface;

    /**
     * @return UsesGeneratorInterface
     */
    public function createUsesBuilder(): UsesGeneratorInterface;

    /**
     * @return TraitsGeneratorInterface
     */
    public function createTraitsBuilder(): TraitsGeneratorInterface;

    /**
     * @return PropertiesGeneratorInterface
     */
    public function createPropertiesBuilder(): PropertiesGeneratorInterface;

    /**
     * @return MethodsGeneratorInterface
     */
    public function createMethodsBuilder(): MethodsGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return PropertyGeneratorInterface
     */
    public function createPropertyBuilder(UsesGeneratorInterface $usesBuilder): PropertyGeneratorInterface;

    /**
     * @return PhpDocFactoryInterface
     */
    public function getPhpDocFactory(): PhpDocFactoryInterface;

    /**
     * @return string
     */
    public function getTraitsBuilderClass(): string;

    /**
     * @param string $methodsBuilderClass
     */
    public function setMethodsBuilderClass(string $methodsBuilderClass): void;

    /**
     * @param string $usesBuilderClass
     */
    public function setUsesBuilderClass(string $usesBuilderClass): void;

    /**
     * @param string $traitsBuilderClass
     */
    public function setTraitsBuilderClass(string $traitsBuilderClass): void;

    /**
     * @param string $propertyBuilderClass
     */
    public function setPropertyBuilderClass(string $propertyBuilderClass): void;

    /**
     * @return string
     */
    public function getPropertiesBuilderClass(): string;

    /**
     * @param string $classBuilderClass
     */
    public function setClassBuilderClass(string $classBuilderClass): void;

    /**
     * @return string
     */
    public function getClassBuilderClass(): string;

    /**
     * @return string
     */
    public function getMethodsBuilderClass(): string;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     */
    public function setPhpDocFactory(PhpDocFactoryInterface $phpDocFactory): void;

    /**
     * @return string
     */
    public function getPropertyBuilderClass(): string;

    /**
     * @return string
     */
    public function getUsesBuilderClass(): string;

    /**
     * @param string $propertiesBuilderClass
     */
    public function setPropertiesBuilderClass(string $propertiesBuilderClass): void;
}