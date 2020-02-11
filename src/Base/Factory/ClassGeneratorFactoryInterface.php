<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

interface ClassGeneratorFactoryInterface
{
    /**
     * @return ClassGeneratorInterface
     */
    public function createClassGenerator(): ClassGeneratorInterface;

    /**
     * @return UsesGeneratorInterface
     */
    public function createUsesGenerator(): UsesGeneratorInterface;

    /**
     * @return TraitsGeneratorInterface
     */
    public function createTraitsGenerator(): TraitsGeneratorInterface;

    /**
     * @return PropertiesGeneratorInterface
     */
    public function createPropertiesGenerator(): PropertiesGeneratorInterface;

    /**
     * @return MethodsGeneratorInterface
     */
    public function createMethodsGenerator(): MethodsGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     *
     * @return PropertyGeneratorInterface
     */
    public function createPropertyGenerator(UsesGeneratorInterface $usesGenerator): PropertyGeneratorInterface;

    /**
     * @return PhpDocGeneratorFactoryInterface
     */
    public function getPhpDocGeneratorFactory(): PhpDocGeneratorFactoryInterface;

    /**
     * @return string
     */
    public function getTraitsGeneratorClass(): string;

    /**
     * @param string $methodsGeneratorClass
     */
    public function setMethodsGeneratorClass(string $methodsGeneratorClass): void;

    /**
     * @param string $usesGeneratorClass
     */
    public function setUsesGeneratorClass(string $usesGeneratorClass): void;

    /**
     * @param string $traitsGeneratorClass
     */
    public function setTraitsGeneratorClass(string $traitsGeneratorClass): void;

    /**
     * @param string $propertyGeneratorClass
     */
    public function setPropertyGeneratorClass(string $propertyGeneratorClass): void;

    /**
     * @return string
     */
    public function getPropertiesGeneratorClass(): string;

    /**
     * @param string $classGeneratorClass
     */
    public function setClassGeneratorClass(string $classGeneratorClass): void;

    /**
     * @return string
     */
    public function getClassGeneratorClass(): string;

    /**
     * @return string
     */
    public function getMethodsGeneratorClass(): string;

    /**
     * @param PhpDocGeneratorFactoryInterface $phpDocFactory
     */
    public function setPhpDocGeneratorFactory(PhpDocGeneratorFactoryInterface $phpDocFactory): void;

    /**
     * @return string
     */
    public function getPropertyGeneratorClass(): string;

    /**
     * @return string
     */
    public function getUsesGeneratorClass(): string;

    /**
     * @param string $propertiesGeneratorClass
     */
    public function setPropertiesGeneratorClass(string $propertiesGeneratorClass): void;
}