<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface as BaseMethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method\ModelConstructorBuilderInterface;

interface ModelMethodFactoryInterface extends BaseMethodFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return ModelConstructorBuilderInterface
     */
    public function createModelConstructorBuilder(UsesBuilderInterface $usesBuilder): ModelConstructorBuilderInterface;
}