<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactory as BaseMethodFactory;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;

class ModelMethodFactory extends BaseMethodFactory implements ModelMethodFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createModelConstructorBuilder(UsesGeneratorInterface $usesBuilder): ModelConstructorGeneratorInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->constructorBuilderClass(
            $usesBuilder,
            $phpDocBuilder,
            $this->createMethodParameterBuilder($usesBuilder)
        );
    }
}