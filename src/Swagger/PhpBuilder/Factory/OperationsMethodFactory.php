<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactory as BaseMethodFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Operation\OperationsMethodBuilderInterface;

class OperationsMethodFactory extends BaseMethodFactory implements OperationsMethodFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createOperationMethodBuilder(UsesBuilderInterface $usesBuilder): OperationsMethodBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->methodBuilderClass($usesBuilder, $phpDocBuilder, $this);
    }
}