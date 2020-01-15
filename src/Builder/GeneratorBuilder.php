<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Builder;

use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ArrayGetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\GetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\IsserSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodParameterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\PropertyMethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\MethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\PropertiesBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\TraitsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilder;

abstract class GeneratorBuilder implements GeneratorBuilderInterface
{
    /** @var ClassFactoryInterface */
    protected $classFactory;
    /** @var PhpDocFactoryInterface */
    protected $phpDocFactory;
    /** @var MethodFactoryInterface */
    protected $methodFactory;

    public function __construct()
    {
        $this->phpDocFactory = new PhpDocFactory(
            PhpDocBuilder::class
        );

        $this->classFactory = new ClassFactory(
            $this->phpDocFactory,
            ClassBuilder::class,
            UsesBuilder::class,
            TraitsBuilder::class,
            PropertiesBuilder::class,
            MethodsBuilder::class,
            PropertyBuilder::class
        );

        $this->methodFactory = new MethodFactory(
            $this->phpDocFactory,
            MethodBuilder::class,
            ConstructorBuilder::class,
            MethodParameterBuilder::class,
            GetterSetterBuilder::class,
            IsserSetterBuilder::class,
            ArrayGetterSetterBuilder::class,
            PropertyMethodsBuilder::class
        );
    }

    /**
     * {@inheritDoc}
     */
    abstract public function build(): GeneratorInterface;

    /**
     * @return ClassFactoryInterface
     */
    public function getClassFactory(): ClassFactoryInterface
    {
        return $this->classFactory;
    }

    /**
     * @param ClassFactoryInterface $classFactory
     */
    public function setClassFactory(ClassFactoryInterface $classFactory): void
    {
        $this->classFactory = $classFactory;
    }
}