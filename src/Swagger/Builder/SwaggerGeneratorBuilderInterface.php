<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Builder;

use Prometee\SwaggerClientGenerator\Base\Builder\GeneratorBuilderInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\ClassViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\MethodViewFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Factory\PhpDocGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelClassGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\ModelMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Factory\OperationsMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerOperationsGeneratorInterface;

interface SwaggerGeneratorBuilderInterface extends GeneratorBuilderInterface
{
    /**
     * @param ClassViewFactoryInterface $classViewFactory
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return SwaggerModelGeneratorInterface
     */
    public function createSwaggerModelGenerator(
        ClassViewFactoryInterface $classViewFactory,
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): SwaggerModelGeneratorInterface;

    /**
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return ModelMethodGeneratorFactoryInterface
     */
    public function createModelMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ModelMethodGeneratorFactoryInterface;

    /**
     * @param ModelMethodGeneratorFactoryInterface $modelMethodGeneratorFactory
     */
    public function configureModelMethodGeneratorFactory(
        ModelMethodGeneratorFactoryInterface $modelMethodGeneratorFactory
    ): void;

    /**
     * @param ClassViewFactoryInterface $classViewFactory
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return SwaggerOperationsGeneratorInterface
     */
    public function createSwaggerOperationsGenerator(
        ClassViewFactoryInterface $classViewFactory,
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): SwaggerOperationsGeneratorInterface;

    /**
     * @param ClassViewFactoryInterface $classViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return ClassGeneratorFactoryInterface
     */
    public function createOperationsClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ClassGeneratorFactoryInterface;

    /**
     * @param MethodViewFactoryInterface $methodViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return OperationsMethodGeneratorFactoryInterface
     */
    public function createOperationsMethodGeneratorFactory(
        MethodViewFactoryInterface $methodViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): OperationsMethodGeneratorFactoryInterface;

    /**
     * @param OperationsMethodGeneratorFactoryInterface $operationsMethodGeneratorFactory
     */
    public function configureOperationsMethodGeneratorFactory(
        OperationsMethodGeneratorFactoryInterface $operationsMethodGeneratorFactory
    ): void;

    /**
     * @param ClassViewFactoryInterface $classViewFactory
     * @param PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
     *
     * @return ModelClassGeneratorFactoryInterface
     */
    public function createModelClassGeneratorFactory(
        ClassViewFactoryInterface $classViewFactory,
        PhpDocGeneratorFactoryInterface $phpDocGeneratorFactory
    ): ModelClassGeneratorFactoryInterface;

    /**
     * @param ClassGeneratorFactoryInterface $classGeneratorFactory
     */
    public function configureModelClassGeneratorFactory(
        ClassGeneratorFactoryInterface $classGeneratorFactory
    ): void;
}