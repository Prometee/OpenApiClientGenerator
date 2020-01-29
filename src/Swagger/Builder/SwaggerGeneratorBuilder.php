<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Builder;

use Prometee\SwaggerClientGenerator\Base\Builder\GeneratorBuilder;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ArrayGetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\GetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\IsserSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodParameterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\PropertyMethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerModelHelper;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerOperationsHelper;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelClassFactory;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelMethodFactory;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\OperationsMethodFactory;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\OperationsMethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Operation\OperationsMethodGenerator;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerGenerator;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerModelGenerator;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerOperationsGenerator;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerOperationsGeneratorInterface;

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
            ClassGenerator::class,
            UsesGenerator::class,
            TraitsGenerator::class,
            ModelPropertiesGenerator::class,
            MethodsGenerator::class,
            ModelPropertyGenerator::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createModelMethodFactory(PhpDocFactoryInterface $phpDocFactory): ModelMethodFactoryInterface
    {
        return new ModelMethodFactory(
            $phpDocFactory,
            MethodGenerator::class,
            ModelConstructorGenerator::class,
            MethodParameterGenerator::class,
            GetterSetterGenerator::class,
            IsserSetterGenerator::class,
            ArrayGetterSetterGenerator::class,
            PropertyMethodsGenerator::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createOperationsMethodFactory(PhpDocFactoryInterface $phpDocFactory): OperationsMethodFactoryInterface
    {
        return new OperationsMethodFactory(
            $phpDocFactory,
            OperationsMethodGenerator::class,
            ConstructorGenerator::class,
            MethodParameterGenerator::class,
            GetterSetterGenerator::class,
            IsserSetterGenerator::class,
            ArrayGetterSetterGenerator::class,
            PropertyMethodsGenerator::class
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