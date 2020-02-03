<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;

interface ModelConstructorGeneratorInterface extends ConstructorGeneratorInterface
{
    /**
     * @param ModelPropertiesGeneratorInterface $modelPropertiesGenerator
     */
    public function configureFromPropertiesGenerator(ModelPropertiesGeneratorInterface $modelPropertiesGenerator): void;

    /**
     * @param ModelPropertyGeneratorInterface $modelPropertyGenerator
     */
    public function configureBodyFromPropertyGenerator(ModelPropertyGeneratorInterface $modelPropertyGenerator): void;

    /**
     * @param ModelPropertyGeneratorInterface $modelPropertyGenerator
     */
    public function configureParameterFromPropertyGenerator(ModelPropertyGeneratorInterface $modelPropertyGenerator): void;
}