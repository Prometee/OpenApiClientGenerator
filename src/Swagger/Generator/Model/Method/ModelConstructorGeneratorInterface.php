<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;

interface ModelConstructorGeneratorInterface extends ConstructorGeneratorInterface
{
    /**
     * @param ModelPropertiesGeneratorInterface $modelPropertiesBuilder
     */
    public function configureFromPropertiesBuilder(ModelPropertiesGeneratorInterface $modelPropertiesBuilder): void;

    /**
     * @param ModelPropertyGeneratorInterface $modelPropertyBuilder
     */
    public function configureBodyFromPropertyBuilder(ModelPropertyGeneratorInterface $modelPropertyBuilder): void;

    /**
     * @param ModelPropertyGeneratorInterface $modelPropertyBuilder
     */
    public function configureParameterFromPropertyBuilder(ModelPropertyGeneratorInterface $modelPropertyBuilder): void;
}