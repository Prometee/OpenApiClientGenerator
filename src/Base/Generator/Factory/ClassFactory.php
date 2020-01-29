<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

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
    public function createClassBuilder(): ClassGeneratorInterface
    {
        return new $this->classBuilderClass(
            $this->createUsesBuilder(),
            $this->createPropertiesBuilder(),
            $this->createMethodsBuilder(),
            $this->createTraitsBuilder()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createUsesBuilder(): UsesGeneratorInterface
    {
        return new $this->usesBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createTraitsBuilder(): TraitsGeneratorInterface
    {
        return new $this->traitsBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertiesBuilder(): PropertiesGeneratorInterface
    {
        return new $this->propertiesBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodsBuilder(): MethodsGeneratorInterface
    {
        return new $this->methodsBuilderClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyBuilder(UsesGeneratorInterface $usesBuilder): PropertyGeneratorInterface
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