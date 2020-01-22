<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other\ModelPropertiesBuilderInterface;

interface ModelConstructorBuilderInterface extends ConstructorBuilderInterface
{
    /**
     * @param ModelPropertiesBuilderInterface $modelPropertiesBuilder
     */
    public function configureFromPropertiesBuilder(ModelPropertiesBuilderInterface $modelPropertiesBuilder): void;

    /**
     * @param ModelPropertyBuilderInterface $modelPropertyBuilder
     */
    public function configureBodyFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void;

    /**
     * @param ModelPropertyBuilderInterface $modelPropertyBuilder
     */
    public function configureParameterFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void;
}