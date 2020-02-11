<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Builder;

use Prometee\SwaggerClientGenerator\Base\Builder\GeneratorBuilder;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\PhpGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerModelHelper;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerOperationsHelper;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelClassGeneratorFactory;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelMethodGeneratorFactory;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\OperationsMethodGeneratorFactory;
use Prometee\SwaggerClientGenerator\Swagger\Factory\OperationsMethodGeneratorFactoryInterface;
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
    public function build(): PhpGeneratorInterface
    {
        $phpDocViewFactory = $this->createPhpDocViewFactory();
        $classViewFactory = $this->createClassViewFactory();
        $methodViewFactory = $this->createMethodViewFactory();
        $phpDocGeneratorFactory = $this->createPhpDocGeneratorFactory($phpDocViewFactory);

        return new SwaggerGenerator(
            $this->createSwaggerModelGenerator(
                $classViewFactory,
                $methodViewFactory,
                $phpDocGeneratorFactory
            ),
            $this->createSwaggerOperationsGenerator(
                $classViewFactory,
                $methodViewFactory,
                $phpDocGeneratorFactory
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createSwaggerModelGenerator(
        ClassViewFactoryInterface $classViewFactory,
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): SwaggerModelGeneratorInterface
    {
        $modelClassGeneratorFactory = $this->createModelClassGeneratorFactory($classViewFactory, $phpDocGeneratorFactory);
        $modelMethodGeneratorFactory = $this->createModelMethodGeneratorFactory($methodViewFactory, $phpDocGeneratorFactory);
        $swaggerModelHelper = new SwaggerModelHelper();

        return new SwaggerModelGenerator(
            $modelClassGeneratorFactory,
            $modelMethodGeneratorFactory,
            $swaggerModelHelper
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createModelClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ModelClassGeneratorFactoryInterface
    {
        $modelClassGeneratorFactory = new ModelClassGeneratorFactory(
            $classViewFactory,
            $phpDocGeneratorFactory
        );

        $this->configureModelClassGeneratorFactory($modelClassGeneratorFactory);

        return $modelClassGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureModelClassGeneratorFactory(ClassGeneratorFactoryInterface $classGeneratorFactory): void
    {
        parent::configureClassGeneratorFactory($classGeneratorFactory);

        $classGeneratorFactory->setPropertiesGeneratorClass(ModelPropertiesGenerator::class);
        $classGeneratorFactory->setPropertyGeneratorClass(ModelPropertyGenerator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createModelMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ModelMethodGeneratorFactoryInterface
    {
        $modelMethodGeneratorFactory = new ModelMethodGeneratorFactory(
            $methodViewFactory,
            $phpDocGeneratorFactory
        );

        $this->configureModelMethodGeneratorFactory($modelMethodGeneratorFactory);

        return $modelMethodGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureModelMethodGeneratorFactory(
        ModelMethodGeneratorFactoryInterface $modelMethodGeneratorFactory
    ): void
    {
        parent::configureMethodGeneratorFactory($modelMethodGeneratorFactory);

        $modelMethodGeneratorFactory
            ->setModelConstructorGeneratorClass(ModelConstructorGenerator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createSwaggerOperationsGenerator(
        ClassViewFactoryInterface $classViewFactory,
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): SwaggerOperationsGeneratorInterface
    {
        $swaggerOperationsClassFactory = $this->createOperationsClassGeneratorFactory($classViewFactory, $phpDocGeneratorFactory);
        $swaggerOperationsMethodFactory = $this->createOperationsMethodGeneratorFactory($methodViewFactory, $phpDocGeneratorFactory);
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
    public function createOperationsClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ClassGeneratorFactoryInterface
    {
        return $this->createClassGeneratorFactory($classViewFactory, $phpDocGeneratorFactory);
    }

    /**
     * {@inheritDoc}
     */
    public function createOperationsMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): OperationsMethodGeneratorFactoryInterface
    {
        $operationsMethodGeneratorFactory = new OperationsMethodGeneratorFactory(
            $methodViewFactory,
            $phpDocGeneratorFactory
        );

        $this->configureOperationsMethodGeneratorFactory($operationsMethodGeneratorFactory);

        return $operationsMethodGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOperationsMethodGeneratorFactory(
        OperationsMethodGeneratorFactoryInterface $operationsMethodGeneratorFactory
    ): void
    {
        parent::configureMethodGeneratorFactory($operationsMethodGeneratorFactory);

        $operationsMethodGeneratorFactory
            ->setOperationsMethodGeneratorClass(OperationsMethodGenerator::class);
    }
}