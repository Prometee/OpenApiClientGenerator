<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface as BaseMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Operation\OperationsMethodBuilderInterface;

interface OperationsMethodFactoryInterface extends BaseMethodFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return OperationsMethodBuilderInterface
     */
    public function createOperationMethodBuilder(UsesBuilderInterface $usesBuilder): OperationsMethodBuilderInterface;
}