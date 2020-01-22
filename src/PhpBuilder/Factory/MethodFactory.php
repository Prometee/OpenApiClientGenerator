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

class MethodFactory implements MethodFactoryInterface
{
    /** @var PhpDocFactoryInterface */
    protected $phpDocFactory;
    /** @var string */
    protected $methodBuilderClass;
    /** @var string */
    protected $constructorBuilderClass;
    /** @var string */
    protected $methodParameterBuilderClass;
    /** @var string */
    protected $getterSetterBuilderClass;
    /** @var string */
    protected $isserSetterBuilderClass;
    /** @var string */
    protected $arrayGetterSetterBuilderClass;
    /** @var string */
    protected $propertyMethodsBuilderClass;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     * @param string $methodBuilderClass
     * @param string $constructorBuilderClass
     * @param string $methodParameterBuilderClass
     * @param string $getterSetterBuilderClass
     * @param string $isserSetterBuilderClass
     * @param string $arrayGetterSetterBuilderClass
     * @param string $propertyMethodsBuilderClass
     */
    public function __construct(
        PhpDocFactoryInterface $phpDocFactory,
        string $methodBuilderClass,
        string $constructorBuilderClass,
        string $methodParameterBuilderClass,
        string $getterSetterBuilderClass,
        string $isserSetterBuilderClass,
        string $arrayGetterSetterBuilderClass,
        string $propertyMethodsBuilderClass
    )
    {
        $this->phpDocFactory = $phpDocFactory;
        $this->methodBuilderClass = $methodBuilderClass;
        $this->constructorBuilderClass = $constructorBuilderClass;
        $this->methodParameterBuilderClass = $methodParameterBuilderClass;
        $this->getterSetterBuilderClass = $getterSetterBuilderClass;
        $this->isserSetterBuilderClass = $isserSetterBuilderClass;
        $this->arrayGetterSetterBuilderClass = $arrayGetterSetterBuilderClass;
        $this->propertyMethodsBuilderClass = $propertyMethodsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodBuilder(UsesBuilderInterface $usesBuilder): MethodBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->methodBuilderClass($usesBuilder, $phpDocBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function createConstructorBuilder(UsesBuilderInterface $usesBuilder): ConstructorBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->constructorBuilderClass($usesBuilder, $phpDocBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodParameterBuilder(UsesBuilderInterface $usesBuilder): MethodParameterBuilderInterface
    {
        return new $this->methodParameterBuilderClass($usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyMethodsBuilder(UsesBuilderInterface $usesBuilder): PropertyMethodsBuilderInterface
    {
        return new $this->propertyMethodsBuilderClass($usesBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function createGetterSetterBuilder(UsesBuilderInterface $usesBuilder): GetterSetterBuilderInterface
    {
        return new $this->getterSetterBuilderClass($usesBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function createIsserSetterBuilderBuilder(UsesBuilderInterface $usesBuilder): IsserSetterBuilderInterface
    {
        return new $this->isserSetterBuilderClass($usesBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function createArrayGetterSetterBuilder(UsesBuilderInterface $usesBuilder): ArrayGetterSetterBuilderInterface
    {
        return new $this->arrayGetterSetterBuilderClass($usesBuilder, $this);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpDocFactory(): PhpDocFactoryInterface
    {
        return $this->phpDocFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setPhpDocFactory(PhpDocFactoryInterface $phpDocFactory): void
    {
        $this->phpDocFactory = $phpDocFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodBuilderClass(): string
    {
        return $this->methodBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodBuilderClass(string $methodBuilderClass): void
    {
        $this->methodBuilderClass = $methodBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getConstructorBuilderClass(): string
    {
        return $this->constructorBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setConstructorBuilderClass(string $constructorBuilderClass): void
    {
        $this->constructorBuilderClass = $constructorBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodParameterBuilderClass(): string
    {
        return $this->methodParameterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodParameterBuilderClass(string $methodParameterBuilderClass): void
    {
        $this->methodParameterBuilderClass = $methodParameterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getGetterSetterBuilderClass(): string
    {
        return $this->getterSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setGetterSetterBuilderClass(string $getterSetterBuilderClass): void
    {
        $this->getterSetterBuilderClass = $getterSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getIsserSetterBuilderClass(): string
    {
        return $this->isserSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsserSetterBuilderClass(string $isserSetterBuilderClass): void
    {
        $this->isserSetterBuilderClass = $isserSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayGetterSetterBuilderClass(): string
    {
        return $this->arrayGetterSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setArrayGetterSetterBuilderClass(string $arrayGetterSetterBuilderClass): void
    {
        $this->arrayGetterSetterBuilderClass = $arrayGetterSetterBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyMethodsBuilderClass(): string
    {
        return $this->propertyMethodsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertyMethodsBuilderClass(string $propertyMethodsBuilderClass): void
    {
        $this->propertyMethodsBuilderClass = $propertyMethodsBuilderClass;
    }
}