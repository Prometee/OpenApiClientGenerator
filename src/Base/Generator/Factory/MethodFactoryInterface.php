<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ArrayGetterSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\GetterSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\IsserSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodParameterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\PropertyMethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

interface MethodFactoryInterface
{
    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return MethodGeneratorInterface
     */
    public function createMethodBuilder(UsesGeneratorInterface $usesBuilder): MethodGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return ConstructorGeneratorInterface
     */
    public function createConstructorBuilder(UsesGeneratorInterface $usesBuilder): ConstructorGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return MethodParameterGeneratorInterface
     */
    public function createMethodParameterBuilder(UsesGeneratorInterface $usesBuilder): MethodParameterGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return PropertyMethodsGeneratorInterface
     */
    public function createPropertyMethodsBuilder(UsesGeneratorInterface $usesBuilder): PropertyMethodsGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return GetterSetterGeneratorInterface
     */
    public function createGetterSetterBuilder(UsesGeneratorInterface $usesBuilder): GetterSetterGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return IsserSetterGeneratorInterface
     */
    public function createIsserSetterBuilderBuilder(UsesGeneratorInterface $usesBuilder): IsserSetterGeneratorInterface;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return ArrayGetterSetterGeneratorInterface
     */
    public function createArrayGetterSetterBuilder(UsesGeneratorInterface $usesBuilder): ArrayGetterSetterGeneratorInterface;

    /**
     * @return PhpDocFactoryInterface
     */
    public function getPhpDocFactory(): PhpDocFactoryInterface;

    /**
     * @param string $getterSetterBuilderClass
     */
    public function setGetterSetterBuilderClass(string $getterSetterBuilderClass): void;

    /**
     * @return string
     */
    public function getMethodBuilderClass(): string;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     */
    public function setPhpDocFactory(PhpDocFactoryInterface $phpDocFactory): void;

    /**
     * @param string $arrayGetterSetterBuilderClass
     */
    public function setArrayGetterSetterBuilderClass(string $arrayGetterSetterBuilderClass): void;

    /**
     * @param string $methodBuilderClass
     */
    public function setMethodBuilderClass(string $methodBuilderClass): void;

    /**
     * @param string $propertyMethodsBuilderClass
     */
    public function setPropertyMethodsBuilderClass(string $propertyMethodsBuilderClass): void;

    /**
     * @param string $methodParameterBuilderClass
     */
    public function setMethodParameterBuilderClass(string $methodParameterBuilderClass): void;

    /**
     * @param string $isserSetterBuilderClass
     */
    public function setIsserSetterBuilderClass(string $isserSetterBuilderClass): void;

    /**
     * @return string
     */
    public function getConstructorBuilderClass(): string;

    /**
     * @param string $constructorBuilderClass
     */
    public function setConstructorBuilderClass(string $constructorBuilderClass): void;

    /**
     * @return string
     */
    public function getMethodParameterBuilderClass(): string;

    /**
     * @return string
     */
    public function getPropertyMethodsBuilderClass(): string;

    /**
     * @return string
     */
    public function getArrayGetterSetterBuilderClass(): string;

    /**
     * @return string
     */
    public function getIsserSetterBuilderClass(): string;

    /**
     * @return string
     */
    public function getGetterSetterBuilderClass(): string;
}