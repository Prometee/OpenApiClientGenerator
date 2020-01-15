<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Builder;

use Prometee\SwaggerClientBuilder\Builder\GeneratorBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGeneratorInterface;

interface SwaggerGeneratorBuilderInterface extends GeneratorBuilderInterface
{
    /**
     * @return SwaggerModelGeneratorInterface
     */
    public function getSwaggerModelGenerator(): SwaggerModelGeneratorInterface;

    /**
     * @param SwaggerModelGeneratorInterface $swaggerModelGenerator
     */
    public function setSwaggerModelGenerator(SwaggerModelGeneratorInterface $swaggerModelGenerator): void;

    /**
     * @return SwaggerOperationsGeneratorInterface
     */
    public function getSwaggerOperationsGenerator(): SwaggerOperationsGeneratorInterface;

    /**
     * @param SwaggerOperationsGeneratorInterface $swaggerOperationsGenerator
     */
    public function setSwaggerOperationsGenerator(SwaggerOperationsGeneratorInterface $swaggerOperationsGenerator): void;
}