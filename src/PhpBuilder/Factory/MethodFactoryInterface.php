<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ArrayGetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\GetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\IsserSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\PropertyMethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;

interface MethodFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return MethodBuilderInterface
     */
    public function createMethodBuilder(UsesBuilderInterface $usesBuilder): MethodBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return ConstructorBuilderInterface
     */
    public function createConstructorBuilder(UsesBuilderInterface $usesBuilder): ConstructorBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return MethodParameterBuilderInterface
     */
    public function createMethodParameterBuilder(UsesBuilderInterface $usesBuilder): MethodParameterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PropertyMethodsBuilderInterface
     */
    public function createPropertyMethodsBuilder(UsesBuilderInterface $usesBuilder): PropertyMethodsBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return GetterSetterBuilderInterface
     */
    public function createGetterSetterBuilder(UsesBuilderInterface $usesBuilder): GetterSetterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return IsserSetterBuilderInterface
     */
    public function createIsserSetterBuilderBuilder(UsesBuilderInterface $usesBuilder): IsserSetterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return ArrayGetterSetterBuilderInterface
     */
    public function createArrayGetterSetterBuilder(UsesBuilderInterface $usesBuilder): ArrayGetterSetterBuilderInterface;

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