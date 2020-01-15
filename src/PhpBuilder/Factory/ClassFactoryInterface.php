<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\ClassBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;

interface ClassFactoryInterface
{
    /**
     * @return ClassBuilderInterface
     */
    public function createClassBuilder(): ClassBuilderInterface;

    /**
     * @return UsesBuilderInterface
     */
    public function createUsesBuilder(): UsesBuilderInterface;

    /**
     * @return TraitsBuilderInterface
     */
    public function createTraitsBuilder(): TraitsBuilderInterface;

    /**
     * @return PropertiesBuilderInterface
     */
    public function createPropertiesBuilder(): PropertiesBuilderInterface;

    /**
     * @return MethodsBuilderInterface
     */
    public function createMethodsBuilder(): MethodsBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PropertyBuilderInterface
     */
    public function createPropertyBuilder(UsesBuilderInterface $usesBuilder): PropertyBuilderInterface;
}