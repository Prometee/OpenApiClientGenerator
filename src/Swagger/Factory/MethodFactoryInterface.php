<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\OperationMethodBuilderInterface;

interface MethodFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return OperationMethodBuilderInterface
     */
    public function createOperationMethodBuilder(UsesBuilderInterface $usesBuilder): OperationMethodBuilderInterface;
}