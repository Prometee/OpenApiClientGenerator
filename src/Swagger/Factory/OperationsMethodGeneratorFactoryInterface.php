<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Factory;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface as BaseMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Operation\OperationsMethodGeneratorInterface;

interface OperationsMethodGeneratorFactoryInterface extends BaseMethodGeneratorFactoryInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     *
     * @return OperationsMethodGeneratorInterface
     */
    public function createOperationMethodGenerator(UsesGeneratorInterface $usesGenerator): OperationsMethodGeneratorInterface;

    /**
     * @param string $operationsMethodGeneratorClass
     */
    public function setOperationsMethodGeneratorClass(string $operationsMethodGeneratorClass): void;
}