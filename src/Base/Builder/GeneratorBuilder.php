<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Builder;

use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassViewFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodViewFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocGeneratorFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocViewFactory;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\ArrayGetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\ConstructorGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\GetterSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\IsserSetterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodParameterGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\PropertyMethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\TraitsGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGenerator;
use Prometee\SwaggerClientGenerator\Base\View\Attribute\PropertyView;
use Prometee\SwaggerClientGenerator\Base\View\ClassView;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodParameterView;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodView;
use Prometee\SwaggerClientGenerator\Base\View\Other\MethodsView;
use Prometee\SwaggerClientGenerator\Base\View\Other\PropertiesView;
use Prometee\SwaggerClientGenerator\Base\View\Other\TraitsView;
use Prometee\SwaggerClientGenerator\Base\View\Other\UsesView;
use Prometee\SwaggerClientGenerator\Base\View\PhpDoc\PhpDocView;

abstract class GeneratorBuilder implements GeneratorBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function createPhpDocGeneratorFactory(
        PhpDocViewFactoryInterface $phpDocViewFactory
    ): PhpDocGeneratorFactoryInterface
    {
        $phpDocGeneratorFactory = new PhpDocGeneratorFactory(
            $phpDocViewFactory
        );

        $this->configurePhpDocGeneratorFactory($phpDocGeneratorFactory);

        return $phpDocGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configurePhpDocGeneratorFactory(PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory): void
    {
        $phpDocGeneratorFactory->setPhpDocGeneratorClass(PhpDocGenerator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createPhpDocViewFactory(): PhpDocViewFactoryInterface
    {
        $phpDocViewFactory = new PhpDocViewFactory();

        $this->configurePhpDocViewFactory($phpDocViewFactory);

        return $phpDocViewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configurePhpDocViewFactory(PhpDocViewFactoryInterface $phpDocViewFactory): void
    {
        $phpDocViewFactory->setPhpDocViewClass(PhpDocView::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ClassGeneratorFactoryInterface
    {
        $classGeneratorFactory = new ClassGeneratorFactory(
            $classViewFactory,
            $phpDocGeneratorFactory
        );

        $this->configureClassGeneratorFactory($classGeneratorFactory);

        return $classGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureClassGeneratorFactory(ClassGeneratorFactoryInterface $classGeneratorFactory): void
    {
        $classGeneratorFactory->setClassGeneratorClass(ClassGenerator::class);
        $classGeneratorFactory->setUsesGeneratorClass(UsesGenerator::class);
        $classGeneratorFactory->setTraitsGeneratorClass(TraitsGenerator::class);
        $classGeneratorFactory->setPropertiesGeneratorClass(PropertiesGenerator::class);
        $classGeneratorFactory->setMethodsGeneratorClass(MethodsGenerator::class);
        $classGeneratorFactory->setPropertyGeneratorClass(PropertyGenerator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createClassViewFactory(): ClassViewFactoryInterface
    {
        $classViewFactory = new ClassViewFactory();

        $this->configureClassViewFactory($classViewFactory);

        return $classViewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureClassViewFactory(ClassViewFactoryInterface $classViewFactory): void
    {
        $classViewFactory->setUsesViewClass(UsesView::class);
        $classViewFactory->setClassViewClass(ClassView::class);
        $classViewFactory->setTraitsViewClass(TraitsView::class);
        $classViewFactory->setPropertiesViewClass(PropertiesView::class);
        $classViewFactory->setPropertyViewClass(PropertyView::class);
        $classViewFactory->setMethodsViewClass(MethodsView::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodViewFactory(): MethodViewFactoryInterface
    {
        $methodViewFactory = new MethodViewFactory();

        $this->configureMethodViewFactory($methodViewFactory);

        return $methodViewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureMethodViewFactory(MethodViewFactoryInterface $methodViewFactory): void
    {
        $methodViewFactory->setMethodViewClass(MethodView::class);
        $methodViewFactory->setMethodParameterClass(MethodParameterView::class);
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactoryFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): MethodGeneratorFactoryInterface
    {
        $methodGeneratorFactory = new MethodGeneratorFactory(
            $this->createMethodViewFactory(),
            $phpDocGeneratorFactory
        );

        $this->configureMethodGeneratorFactory($methodGeneratorFactory);

        return $methodGeneratorFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function configureMethodGeneratorFactory(MethodGeneratorFactoryInterface $methodGeneratorFactory): void
    {
        $methodGeneratorFactory->setMethodGeneratorClass(MethodGenerator::class);
        $methodGeneratorFactory->setConstructorGeneratorClass(ConstructorGenerator::class);
        $methodGeneratorFactory->setMethodParameterGeneratorClass(MethodParameterGenerator::class);
        $methodGeneratorFactory->setGetterSetterGeneratorClass(GetterSetterGenerator::class);
        $methodGeneratorFactory->setIsserSetterGeneratorClass(IsserSetterGenerator::class);
        $methodGeneratorFactory->setArrayGetterSetterGeneratorClass(ArrayGetterSetterGenerator::class);
        $methodGeneratorFactory->setPropertyMethodsGeneratorClass(PropertyMethodsGenerator::class);
    }
}