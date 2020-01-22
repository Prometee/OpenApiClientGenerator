<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Builder;

use Prometee\SwaggerClientBuilder\Builder\GeneratorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelClassFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\ModelMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory\OperationsMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGeneratorInterface;

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