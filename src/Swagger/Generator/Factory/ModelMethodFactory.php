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
    public function createModelConstructorGenerator(UsesGeneratorInterface $usesGenerator): ModelConstructorGeneratorInterface
    {
        $phpDocGenerator = $this->phpDocFactory->createPhpDocGenerator($usesGenerator);
        return new $this->constructorGeneratorClass(
            $usesGenerator,
            $phpDocGenerator,
            $this->createMethodParameterGenerator($usesGenerator)
        );
    }
}