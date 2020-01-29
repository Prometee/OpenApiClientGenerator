<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Builder;

use Prometee\SwaggerClientGenerator\Base\Generator\Factory\ClassFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\PhpDocFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\ClassGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ArrayGetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\GetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\IsserSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\MethodParameterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\PropertyMethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGenerator;

abstract class GeneratorBuilder implements GeneratorBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function createPhpDocFactory(): PhpDocFactoryInterface
    {
        return new PhpDocFactory(
            PhpDocGenerator::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createClassFactory(PhpDocFactoryInterface $phpDocFactory): ClassFactoryInterface
    {
        return new ClassFactory(
            $phpDocFactory,
            ClassGenerator::class,
            UsesGenerator::class,
            TraitsGenerator::class,
            PropertiesGenerator::class,
            MethodsGenerator::class,
            PropertyGenerator::class
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodFactory(PhpDocFactoryInterface $phpDocFactory): MethodFactoryInterface
    {
        return new MethodFactory(
            $phpDocFactory,
            MethodGenerator::class,
            ConstructorGenerator::class,
            MethodParameterGenerator::class,
            GetterSetterGenerator::class,
            IsserSetterGenerator::class,
            ArrayGetterSetterGenerator::class,
            PropertyMethodsGenerator::class
        );
}
}