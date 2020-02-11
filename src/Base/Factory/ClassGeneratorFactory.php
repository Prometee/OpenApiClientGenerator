<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

class ClassGeneratorFactory implements ClassGeneratorFactoryInterface
{
    /** @var PhpDocGeneratorFactoryInterface */
    protected $phpDocGeneratorFactory;
    /** @var ClassViewFactoryInterface */
    protected $classViewFactory;

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
     * @param ClassViewFactoryInterface $classViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     */
    public function __construct(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    )
    {
        $this->phpDocGeneratorFactory = $phpDocGeneratorFactory;
        $this->classViewFactory = $classViewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createClassGenerator(): ClassGeneratorInterface
    {
        $usesGenerator = $this->createUsesGenerator();
        return new $this->classGeneratorClass(
            $this->classViewFactory->createClassView(),
            $usesGenerator,
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
        return new $this->usesGeneratorClass(
            $this->classViewFactory->createUsesView()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createTraitsGenerator(): TraitsGeneratorInterface
    {
        return new $this->traitsGeneratorClass(
            $this->classViewFactory->createTraitsView()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertiesGenerator(): PropertiesGeneratorInterface
    {
        return new $this->propertiesGeneratorClass(
            $this->classViewFactory->createPropertiesView()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodsGenerator(): MethodsGeneratorInterface
    {
        return new $this->methodsGeneratorClass(
            $this->classViewFactory->createMethodsView()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyGenerator(UsesGeneratorInterface $usesGenerator): PropertyGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocGeneratorFactory->createPhpDocGenerator();
        return new $this->propertyGeneratorClass(
            $this->classViewFactory->createPropertyView(),
            $usesGenerator,
            $phpDocGenerator
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