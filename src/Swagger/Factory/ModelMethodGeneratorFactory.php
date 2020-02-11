<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Factory;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactory as BaseMethodGeneratorFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;

class ModelMethodGeneratorFactory extends BaseMethodGeneratorFactory implements ModelMethodGeneratorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createModelConstructorGenerator(UsesGeneratorInterface $usesGenerator): ModelConstructorGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocGeneratorFactory->createPhpDocGenerator();
        return new $this->constructorGeneratorClass(
            $this->methodViewFactory->createMethodView(),
            $usesGenerator,
            $phpDocGenerator,
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }

    /**
     * @inheritDoc
     */
    public function setModelConstructorGeneratorClass(string $modelConstructorGeneratorClass): void
    {
        $this->setConstructorGeneratorClass($modelConstructorGeneratorClass);
    }
}