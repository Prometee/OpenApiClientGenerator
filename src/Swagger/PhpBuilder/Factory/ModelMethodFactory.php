<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactory as BaseMethodFactory;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;

class ModelMethodFactory extends BaseMethodFactory implements ModelMethodFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createModelConstructorBuilder(UsesBuilderInterface $usesBuilder): ModelConstructorBuilderInterface
    {
        $phpDocBuilder = $this->phpDocFactory->createPhpDocBuilder($usesBuilder);
        return new $this->constructorBuilderClass(
            $usesBuilder,
            $phpDocBuilder,
            $this->createMethodParameterBuilder($usesBuilder)
        );
    }
}