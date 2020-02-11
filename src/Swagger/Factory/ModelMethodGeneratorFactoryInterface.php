<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Factory;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface as BaseMethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method\ModelConstructorGeneratorInterface;

interface ModelMethodGeneratorFactoryInterface extends BaseMethodGeneratorFactoryInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     *
     * @return ModelConstructorGeneratorInterface
     */
    public function createModelConstructorGenerator(UsesGeneratorInterface $usesGenerator): ModelConstructorGeneratorInterface;

    /**
     * @param string $modelConstructorGeneratorClass
     */
    public function setModelConstructorGeneratorClass(string $modelConstructorGeneratorClass): void;
}