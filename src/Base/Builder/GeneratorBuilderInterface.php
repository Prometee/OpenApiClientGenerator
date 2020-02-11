<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Builder;

use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\PhpGeneratorInterface;

interface GeneratorBuilderInterface
{
    /**
     * @return PhpGeneratorInterface
     */
    public function build(): PhpGeneratorInterface;

    /**
     * @param PhpDocViewFactoryInterface $phpDocViewFactory
     *
     * @return PhpDocGeneratorFactoryInterface
     */
    public function createPhpDocGeneratorFactory(
        PhpDocViewFactoryInterface $phpDocViewFactory
    ): PhpDocGeneratorFactoryInterface;

    /**
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     */
    public function configurePhpDocGeneratorFactory(PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory): void;

    /**
     * @return PhpDocViewFactoryInterface
     */
    public function createPhpDocViewFactory(): PhpDocViewFactoryInterface;

    /**
     * @param PhpDocViewFactoryInterface $phpDocViewFactory
     */
    public function configurePhpDocViewFactory(PhpDocViewFactoryInterface $phpDocViewFactory): void;

    /**
     * @param ClassViewFactoryInterface $classViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return ClassGeneratorFactoryInterface
     */
    public function createClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ClassGeneratorFactoryInterface;

    /**
     * @param ClassGeneratorFactoryInterface $classGeneratorFactory
     */
    public function configureClassGeneratorFactory(ClassGeneratorFactoryInterface $classGeneratorFactory): void;

    /**
     * @return ClassViewFactoryInterface
     */
    public function createClassViewFactory(): ClassViewFactoryInterface;

    /**
     * @param ClassViewFactoryInterface $classViewFactory
     */
    public function configureClassViewFactory(ClassViewFactoryInterface $classViewFactory): void;

    /**
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return MethodGeneratorFactoryInterface
     */
    public function createMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): MethodGeneratorFactoryInterface;

    /**
     * @param MethodGeneratorFactoryInterface $methodGeneratorFactory
     */
    public function configureMethodGeneratorFactory(MethodGeneratorFactoryInterface $methodGeneratorFactory): void;

    /**
     * @return MethodViewFactoryInterface
     */
    public function createMethodViewFactory(): MethodViewFactoryInterface;

    /**
     * @param MethodViewFactoryInterface $methodViewFactory
     */
    public function configureMethodViewFactory(MethodViewFactoryInterface $methodViewFactory): void;
}