<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

interface ClassFactoryInterface
{
    /**
     * @return ClassBuilderInterface
     */
    public function createClassBuilder(): ClassBuilderInterface;

    /**
     * @return UsesBuilderInterface
     */
    public function createUsesBuilder(): UsesBuilderInterface;

    /**
     * @return TraitsBuilderInterface
     */
    public function createTraitsBuilder(): TraitsBuilderInterface;

    /**
     * @return PropertiesBuilderInterface
     */
    public function createPropertiesBuilder(): PropertiesBuilderInterface;

    /**
     * @return MethodsBuilderInterface
     */
    public function createMethodsBuilder(): MethodsBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PropertyBuilderInterface
     */
    public function createPropertyBuilder(UsesBuilderInterface $usesBuilder): PropertyBuilderInterface;

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