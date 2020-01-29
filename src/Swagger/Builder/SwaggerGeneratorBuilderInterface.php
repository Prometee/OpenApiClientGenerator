<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Builder;

use Prometee\SwaggerClientGenerator\Base\Builder\GeneratorBuilderInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Factory\OperationsMethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerOperationsGeneratorInterface;

interface SwaggerGeneratorBuilderInterface extends GeneratorBuilderInterface
{
    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return SwaggerModelGeneratorInterface
     */
    public function createSwaggerModelGenerator(PhpDocFactoryInterface $phpDocFactory): SwaggerModelGeneratorInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return ModelMethodFactoryInterface
     */
    public function createModelMethodFactory(PhpDocFactoryInterface $phpDocFactory): ModelMethodFactoryInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return SwaggerOperationsGeneratorInterface
     */
    public function createSwaggerOperationsGenerator(PhpDocFactoryInterface $phpDocFactory): SwaggerOperationsGeneratorInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return OperationsMethodFactoryInterface
     */
    public function createOperationsMethodFactory(PhpDocFactoryInterface $phpDocFactory): OperationsMethodFactoryInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return ModelClassFactoryInterface
     */
    public function createModelClassFactory(PhpDocFactoryInterface $phpDocFactory): ModelClassFactoryInterface;
}