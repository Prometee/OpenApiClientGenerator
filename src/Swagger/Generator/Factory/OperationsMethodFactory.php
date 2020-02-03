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
    public function createOperationMethodGenerator(UsesGeneratorInterface $usesGenerator): OperationsMethodGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocFactory->createPhpDocGenerator($usesGenerator);
        return new $this->methodGeneratorClass(
            $usesGenerator,
            $phpDocGenerator,
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }
}