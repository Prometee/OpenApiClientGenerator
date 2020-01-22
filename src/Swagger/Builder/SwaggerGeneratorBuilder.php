<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Builder;

use Prometee\SwaggerClientBuilder\Builder\GeneratorBuilder;
use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\ClassBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ArrayGetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\GetterSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\IsserSetterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodParameterBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\PropertyMethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilder;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilder;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerModelHelper;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelper;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelClassFactory;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelMethodFactory;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\OperationsMethodFactory;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\OperationsMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilder;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilder;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other\ModelPropertiesBuilder;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Operation\OperationsMethodBuilder;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGeneratorInterface;

class SwaggerGeneratorBuilder extends GeneratorBuilder implements SwaggerGeneratorBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSwaggerModelGenerator(PhpDocFactoryInterface $phpDocFactory): SwaggerModelGeneratorInterface
    {
        $swaggerModelClassFactory = $this->createModelClassFactory($phpDocFactory);
        $swaggerModelMethodFactory = $this->createModelMethodFactory($phpDocFactory);
        $swaggerModelHelper = new SwaggerModelHelper();

        return new SwaggerModelGenerator(
            $swaggerModelClassFactory,
            $swaggerModelMethodFactory,
            $swaggerModelHelper
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createSwaggerOperationsGenerator(PhpDocFactoryInterface $phpDocFactory): SwaggerOperationsGeneratorInterface
    {
        $swaggerOperationsClassFactory = $this->createClassFactory($phpDocFactory);
        $swaggerOperationsMethodFactory = $this->createOperationsMethodFactory($phpDocFactory);
        $swaggerOperationsHelper = new SwaggerOperationsHelper();

        return new SwaggerOperationsGenerator(
            $swaggerOperationsClassFactory,
            $swaggerOperationsMethodFactory,
            $swaggerOperationsHelper
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createModelClassFactory(PhpDocFactoryInterface $phpDocFactory): ModelClassFactoryInterface
    {
        return new ModelClassFactory(
            $phpDocFactory,
            ClassBuilder::class,
            UsesBuilder::class,
            TraitsBuilder::class,
            ModelPropertiesBuilder::class,
            MethodsBuilder::class,
            ModelPropertyBuilder::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createModelMethodFactory(PhpDocFactoryInterface $phpDocFactory): ModelMethodFactoryInterface
    {
        return new ModelMethodFactory(
            $phpDocFactory,
            MethodBuilder::class,
            ModelConstructorBuilder::class,
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
    public function createOperationsMethodFactory(PhpDocFactoryInterface $phpDocFactory): OperationsMethodFactoryInterface
    {
        return new OperationsMethodFactory(
            $phpDocFactory,
            OperationsMethodBuilder::class,
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
    public function build(): GeneratorInterface
    {
        $phpDocFactory = $this->createPhpDocFactory();

        return new SwaggerGenerator(
            $this->createSwaggerModelGenerator($phpDocFactory),
            $this->createSwaggerOperationsGenerator($phpDocFactory)
        );
    }
}