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
    public function createMethodBuilder(UsesGeneratorInterface $usesBuilder): MethodGeneratorInterface
    {
        return new $this->methodBuilderClass(
            $usesBuilder,
            $this->phpDocFactory->createPhpDocBuilder($usesBuilder),
            $this->createMethodParameterBuilder($usesBuilder)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createConstructorBuilder(UsesGeneratorInterface $usesBuilder): ConstructorGeneratorInterface
    {
        return new $this->constructorBuilderClass(
            $usesBuilder,
            $this->phpDocFactory->createPhpDocBuilder($usesBuilder)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodParameterBuilder(UsesGeneratorInterface $usesBuilder): MethodParameterGeneratorInterface
    {
        return new $this->methodParameterBuilderClass($usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyMethodsBuilder(UsesGeneratorInterface $usesBuilder): PropertyMethodsGeneratorInterface
    {
        return new $this->propertyMethodsBuilderClass($usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function createGetterSetterBuilder(UsesGeneratorInterface $usesBuilder): GetterSetterGeneratorInterface
    {
        return new $this->getterSetterBuilderClass(
            $usesBuilder,
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createIsserSetterBuilderBuilder(UsesGeneratorInterface $usesBuilder): IsserSetterGeneratorInterface
    {
        return new $this->isserSetterBuilderClass(
            $usesBuilder,
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createArrayGetterSetterBuilder(UsesGeneratorInterface $usesBuilder): ArrayGetterSetterGeneratorInterface
    {
        return new $this->arrayGetterSetterBuilderClass(
            $usesBuilder,
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder),
            $this->createMethodBuilder($usesBuilder)
        );
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