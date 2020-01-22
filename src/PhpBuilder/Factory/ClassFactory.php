<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

class ClassFactory implements ClassFactoryInterface
{
    /** @var PhpDocFactoryInterface */
    protected $phpDocFactory;
    /** @var string */
    protected $classBuilderClass;
    /** @var string */
    protected $usesBuilderClass;
    /** @var string */
    protected $traitsBuilderClass;
    /** @var string */
    protected $propertiesBuilderClass;
    /** @var string */
    protected $methodsBuilderClass;
    /** @var string */
    protected $propertyBuilderClass;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     * @param string $classBuilderClass
     * @param string $usesBuilderClass
     * @param string $traitsBuilderClass
     * @param string $propertiesBuilderClass
     * @param string $methodsBuilderClass
     * @param string $propertyBuilderClass
     */
    public function __construct(
        PhpDocFactoryInterface $phpDocFactory,
        string $classBuilderClass,
        string $usesBuilderClass,
        string $traitsBuilderClass,
        string $propertiesBuilderClass,
        string $methodsBuilderClass,
        string $propertyBuilderClass
    )
    {
        $this->phpDocFactory = $phpDocFactory;
        $this->classBuilderClass = $classBuilderClass;
        $this->usesBuilderClass = $usesBuilderClass;
        $this->traitsBuilderClass = $traitsBuilderClass;
        $this->propertiesBuilderClass = $propertiesBuilderClass;
        $this->methodsBuilderClass = $methodsBuilderClass;
        $this->propertyBuilderClass = $propertyBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createClassBuilder(): ClassBuilderInterface
    {
        return new $this->classBuilderClass($this);
    }

    /**
     * {@inheritDoc}
     */
    public function createUsesBuilder(): UsesBuilderInterface
    {
        return new $this->usesBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createTraitsBuilder(): TraitsBuilderInterface
    {
        return new $this->traitsBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertiesBuilder(): PropertiesBuilderInterface
    {
        return new $this->propertiesBuilderClass($this);
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodsBuilder(): MethodsBuilderInterface
    {
        return new $this->methodsBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyBuilder(UsesBuilderInterface $usesBuilder): PropertyBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->propertyBuilderClass($usesBuilder, $phpDocBuilder);
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
    public function getClassBuilderClass(): string
    {
        return $this->classBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setClassBuilderClass(string $classBuilderClass): void
    {
        $this->classBuilderClass = $classBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsesBuilderClass(): string
    {
        return $this->usesBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesBuilderClass(string $usesBuilderClass): void
    {
        $this->usesBuilderClass = $usesBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getTraitsBuilderClass(): string
    {
        return $this->traitsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsBuilderClass(string $traitsBuilderClass): void
    {
        $this->traitsBuilderClass = $traitsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesBuilderClass(): string
    {
        return $this->propertiesBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesBuilderClass(string $propertiesBuilderClass): void
    {
        $this->propertiesBuilderClass = $propertiesBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsBuilderClass(): string
    {
        return $this->methodsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsBuilderClass(string $methodsBuilderClass): void
    {
        $this->methodsBuilderClass = $methodsBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyBuilderClass(): string
    {
        return $this->propertyBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertyBuilderClass(string $propertyBuilderClass): void
    {
        $this->propertyBuilderClass = $propertyBuilderClass;
    }
}