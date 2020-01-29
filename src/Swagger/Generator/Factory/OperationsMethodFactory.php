<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactory as BaseMethodFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Operation\OperationsMethodGeneratorInterface;

class OperationsMethodFactory extends BaseMethodFactory implements OperationsMethodFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createOperationMethodBuilder(UsesGeneratorInterface $usesBuilder): OperationsMethodGeneratorInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->methodBuilderClass(
            $usesBuilder,
            $phpDocBuilder,
            $this->createMethodParameterBuilder($usesBuilder)
        );
    }
}