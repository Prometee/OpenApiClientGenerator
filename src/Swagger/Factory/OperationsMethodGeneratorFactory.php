<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Factory;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactory as BaseMethodGeneratorFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Operation\OperationsMethodGeneratorInterface;

class OperationsMethodGeneratorFactory extends BaseMethodGeneratorFactory implements OperationsMethodGeneratorFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createOperationMethodGenerator(UsesGeneratorInterface $usesGenerator): OperationsMethodGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocGeneratorFactory->createPhpDocGenerator();
        return new $this->methodGeneratorClass(
            $this->methodViewFactory->createMethodView(),
            $usesGenerator,
            $phpDocGenerator,
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }

    /**
     * @inheritDoc
     */
    public function setOperationsMethodGeneratorClass(string $operationsMethodGeneratorClass): void
    {
        $this->setMethodGeneratorClass($operationsMethodGeneratorClass);
    }
}