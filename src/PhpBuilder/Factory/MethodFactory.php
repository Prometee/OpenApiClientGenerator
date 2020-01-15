<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ArrayGetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\GetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\IsserSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\PropertyMethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;

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
        return new $this->methodBuilderClass($usesBuilder, $phpDocBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function createConstructorBuilder(UsesBuilderInterface $usesBuilder): ConstructorBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->constructorBuilderClass($usesBuilder, $phpDocBuilder);
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
}