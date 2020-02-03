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
     * @param PhpDocFactoryInterface $phpDocFactory
     * @param string $methodGeneratorClass
     * @param string $constructorGeneratorClass
     * @param string $methodParameterGeneratorClass
     * @param string $getterSetterGeneratorClass
     * @param string $isserSetterGeneratorClass
     * @param string $arrayGetterSetterGeneratorClass
     * @param string $propertyMethodsGeneratorClass
     */
    public function __construct(
        PhpDocFactoryInterface $phpDocFactory,
        string $methodGeneratorClass,
        string $constructorGeneratorClass,
        string $methodParameterGeneratorClass,
        string $getterSetterGeneratorClass,
        string $isserSetterGeneratorClass,
        string $arrayGetterSetterGeneratorClass,
        string $propertyMethodsGeneratorClass
    )
    {
        $this->phpDocFactory = $phpDocFactory;
        $this->methodGeneratorClass = $methodGeneratorClass;
        $this->constructorGeneratorClass = $constructorGeneratorClass;
        $this->methodParameterGeneratorClass = $methodParameterGeneratorClass;
        $this->getterSetterGeneratorClass = $getterSetterGeneratorClass;
        $this->isserSetterGeneratorClass = $isserSetterGeneratorClass;
        $this->arrayGetterSetterGeneratorClass = $arrayGetterSetterGeneratorClass;
        $this->propertyMethodsGeneratorClass = $propertyMethodsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodGenerator(UsesGeneratorInterface $usesGenerator): MethodGeneratorInterface
    {
        return new $this->methodGeneratorClass(
            $usesGenerator,
            $this->phpDocFactory->createPhpDocGenerator($usesGenerator),
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createConstructorGenerator(UsesGeneratorInterface $usesGenerator): ConstructorGeneratorInterface
    {
        return new $this->constructorGeneratorClass(
            $usesGenerator,
            $this->phpDocFactory->createPhpDocGenerator($usesGenerator)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodParameterGenerator(UsesGeneratorInterface $usesGenerator): MethodParameterGeneratorInterface
    {
        return new $this->methodParameterGeneratorClass($usesGenerator);
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyMethodsGenerator(UsesGeneratorInterface $usesGenerator): PropertyMethodsGeneratorInterface
    {
        return new $this->propertyMethodsGeneratorClass($usesGenerator);
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