<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;

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
        return new $this->propertiesBuilderClass();
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
}