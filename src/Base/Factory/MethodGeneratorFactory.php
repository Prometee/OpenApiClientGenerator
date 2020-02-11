<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Method\ArrayGetterSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\ConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\GetterSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\IsserSetterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodParameterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\PropertyMethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

class MethodGeneratorFactory implements MethodGeneratorFactoryInterface
{
    /** @var MethodViewFactoryInterface */
    protected $methodViewFactory;
    /** @var PhpDocGeneratorFactoryInterface */
    protected $phpDocGeneratorFactory;

    /** @var string */
    protected $methodGeneratorClass;
    /** @var string */
    protected $constructorGeneratorClass;
    /** @var string */
    protected $methodParameterGeneratorClass;
    /** @var string */
    protected $getterSetterGeneratorClass;
    /** @var string */
    protected $isserSetterGeneratorClass;
    /** @var string */
    protected $arrayGetterSetterGeneratorClass;
    /** @var string */
    protected $propertyMethodsGeneratorClass;

    /**
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     */
    public function __construct(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    )
    {
        $this->methodViewFactory = $methodViewFactory;
        $this->phpDocGeneratorFactory = $phpDocGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodGenerator(UsesGeneratorInterface $usesGenerator): MethodGeneratorInterface
    {
        return new $this->methodGeneratorClass(
            $this->methodViewFactory->createMethodView(),
            $usesGenerator,
            $this->phpDocGeneratorFactory->createPhpDocGenerator(),
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createConstructorGenerator(UsesGeneratorInterface $usesGenerator): ConstructorGeneratorInterface
    {
        return new $this->constructorGeneratorClass(
            $this->methodViewFactory->createMethodView(),
            $usesGenerator,
            $this->phpDocGeneratorFactory->createPhpDocGenerator()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodParameterGenerator(UsesGeneratorInterface $usesGenerator): MethodParameterGeneratorInterface
    {
        return new $this->methodParameterGeneratorClass(
            $this->methodViewFactory->createMethodParameterView(),
            $usesGenerator
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyMethodsGenerator(UsesGeneratorInterface $usesGenerator): PropertyMethodsGeneratorInterface
    {
        return new $this->propertyMethodsGeneratorClass(
            $usesGenerator
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createGetterSetterGenerator(UsesGeneratorInterface $usesGenerator): GetterSetterGeneratorInterface
    {
        return new $this->getterSetterGeneratorClass(
            $usesGenerator,
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createIsserSetterGenerator(UsesGeneratorInterface $usesGenerator): IsserSetterGeneratorInterface
    {
        return new $this->isserSetterGeneratorClass(
            $usesGenerator,
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createArrayGetterSetterGenerator(UsesGeneratorInterface $usesGenerator): ArrayGetterSetterGeneratorInterface
    {
        return new $this->arrayGetterSetterGeneratorClass(
            $usesGenerator,
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator),
            $this->createMethodGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpDocGeneratorFactory(): PhpDocGeneratorFactoryInterface
    {
        return $this->phpDocGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setPhpDocGeneratorFactory(PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory): void
    {
        $this->phpDocGeneratorFactory = $phpDocGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodGeneratorClass(): string
    {
        return $this->methodGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodGeneratorClass(string $methodGeneratorClass): void
    {
        $this->methodGeneratorClass = $methodGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getConstructorGeneratorClass(): string
    {
        return $this->constructorGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setConstructorGeneratorClass(string $constructorGeneratorClass): void
    {
        $this->constructorGeneratorClass = $constructorGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodParameterGeneratorClass(): string
    {
        return $this->methodParameterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodParameterGeneratorClass(string $methodParameterGeneratorClass): void
    {
        $this->methodParameterGeneratorClass = $methodParameterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getGetterSetterGeneratorClass(): string
    {
        return $this->getterSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setGetterSetterGeneratorClass(string $getterSetterGeneratorClass): void
    {
        $this->getterSetterGeneratorClass = $getterSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getIsserSetterGeneratorClass(): string
    {
        return $this->isserSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsserSetterGeneratorClass(string $isserSetterGeneratorClass): void
    {
        $this->isserSetterGeneratorClass = $isserSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayGetterSetterGeneratorClass(): string
    {
        return $this->arrayGetterSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setArrayGetterSetterGeneratorClass(string $arrayGetterSetterGeneratorClass): void
    {
        $this->arrayGetterSetterGeneratorClass = $arrayGetterSetterGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyMethodsGeneratorClass(): string
    {
        return $this->propertyMethodsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertyMethodsGeneratorClass(string $propertyMethodsGeneratorClass): void
    {
        $this->propertyMethodsGeneratorClass = $propertyMethodsGeneratorClass;
    }
}