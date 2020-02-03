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
    protected $classGeneratorClass;
    /** @var string */
    protected $usesGeneratorClass;
    /** @var string */
    protected $traitsGeneratorClass;
    /** @var string */
    protected $propertiesGeneratorClass;
    /** @var string */
    protected $methodsGeneratorClass;
    /** @var string */
    protected $propertyGeneratorClass;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     * @param string $classGeneratorClass
     * @param string $usesGeneratorClass
     * @param string $traitsGeneratorClass
     * @param string $propertiesGeneratorClass
     * @param string $methodsGeneratorClass
     * @param string $propertyGeneratorClass
     */
    public function __construct(
        PhpDocFactoryInterface $phpDocFactory,
        string $classGeneratorClass,
        string $usesGeneratorClass,
        string $traitsGeneratorClass,
        string $propertiesGeneratorClass,
        string $methodsGeneratorClass,
        string $propertyGeneratorClass
    )
    {
        $this->phpDocFactory = $phpDocFactory;
        $this->classGeneratorClass = $classGeneratorClass;
        $this->usesGeneratorClass = $usesGeneratorClass;
        $this->traitsGeneratorClass = $traitsGeneratorClass;
        $this->propertiesGeneratorClass = $propertiesGeneratorClass;
        $this->methodsGeneratorClass = $methodsGeneratorClass;
        $this->propertyGeneratorClass = $propertyGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createClassGenerator(): ClassGeneratorInterface
    {
        return new $this->classGeneratorClass(
            $this->createUsesGenerator(),
            $this->createPropertiesGenerator(),
            $this->createMethodsGenerator(),
            $this->createTraitsGenerator()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createUsesGenerator(): UsesGeneratorInterface
    {
        return new $this->usesGeneratorClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createTraitsGenerator(): TraitsGeneratorInterface
    {
        return new $this->traitsGeneratorClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertiesGenerator(): PropertiesGeneratorInterface
    {
        return new $this->propertiesGeneratorClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodsGenerator(): MethodsGeneratorInterface
    {
        return new $this->methodsGeneratorClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyGenerator(UsesGeneratorInterface $usesGenerator): PropertyGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocFactory->createPhpDocGenerator($usesGenerator);
        return new $this->propertyGeneratorClass($usesGenerator, $phpDocGenerator);
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
    public function getClassGeneratorClass(): string
    {
        return $this->classGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setClassGeneratorClass(string $classGeneratorClass): void
    {
        $this->classGeneratorClass = $classGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsesGeneratorClass(): string
    {
        return $this->usesGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesGeneratorClass(string $usesGeneratorClass): void
    {
        $this->usesGeneratorClass = $usesGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getTraitsGeneratorClass(): string
    {
        return $this->traitsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsGeneratorClass(string $traitsGeneratorClass): void
    {
        $this->traitsGeneratorClass = $traitsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesGeneratorClass(): string
    {
        return $this->propertiesGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesGeneratorClass(string $propertiesGeneratorClass): void
    {
        $this->propertiesGeneratorClass = $propertiesGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsGeneratorClass(): string
    {
        return $this->methodsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsGeneratorClass(string $methodsGeneratorClass): void
    {
        $this->methodsGeneratorClass = $methodsGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyGeneratorClass(): string
    {
        return $this->propertyGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertyGeneratorClass(string $propertyGeneratorClass): void
    {
        $this->propertyGeneratorClass = $propertyGeneratorClass;
    }
}