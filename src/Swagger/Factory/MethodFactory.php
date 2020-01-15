<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\OperationMethodBuilderInterface;

class MethodFactory implements MethodFactoryInterface
{
    /** @var PhpDocFactoryInterface */
    protected $phpDocFactory;
    /** @var string */
    protected $operationMethodBuilderClass;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     * @param string $operationMethodBuilderClass
     */
    public function __construct(
        PhpDocFactoryInterface $phpDocFactory,
        string $operationMethodBuilderClass
    )
    {
        $this->phpDocFactory = $phpDocFactory;
        $this->operationMethodBuilderClass = $operationMethodBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createOperationMethodBuilder(UsesBuilderInterface $usesBuilder): OperationMethodBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->operationMethodBuilderClass($usesBuilder, $phpDocBuilder);
    }
}