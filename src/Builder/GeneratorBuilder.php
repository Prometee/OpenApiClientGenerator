<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Builder;

use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ArrayGetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\GetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\IsserSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodParameterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\PropertyMethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilder;

abstract class GeneratorBuilder implements GeneratorBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function createPhpDocFactory(): PhpDocFactoryInterface
    {
        return new PhpDocFactory(
            PhpDocBuilder::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createClassFactory(PhpDocFactoryInterface $phpDocFactory): ClassFactoryInterface
    {
        return new ClassFactory(
            $phpDocFactory,
            ClassBuilder::class,
            UsesBuilder::class,
            TraitsBuilder::class,
            PropertiesBuilder::class,
            MethodsBuilder::class,
            PropertyBuilder::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodFactory(PhpDocFactoryInterface $phpDocFactory): MethodFactoryInterface
    {
        return new MethodFactory(
            $phpDocFactory,
            MethodBuilder::class,
            ConstructorBuilder::class,
            MethodParameterBuilder::class,
            GetterSetterBuilder::class,
            IsserSetterBuilder::class,
            ArrayGetterSetterBuilder::class,
            PropertyMethodsBuilder::class
        );
}
}