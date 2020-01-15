<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ArrayGetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\ConstructorBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\GetterSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\IsserSetterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodParameterBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\PropertyMethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;

interface MethodFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return MethodBuilderInterface
     */
    public function createMethodBuilder(UsesBuilderInterface $usesBuilder): MethodBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return ConstructorBuilderInterface
     */
    public function createConstructorBuilder(UsesBuilderInterface $usesBuilder): ConstructorBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return MethodParameterBuilderInterface
     */
    public function createMethodParameterBuilder(UsesBuilderInterface $usesBuilder): MethodParameterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PropertyMethodsBuilderInterface
     */
    public function createPropertyMethodsBuilder(UsesBuilderInterface $usesBuilder): PropertyMethodsBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return GetterSetterBuilderInterface
     */
    public function createGetterSetterBuilder(UsesBuilderInterface $usesBuilder): GetterSetterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return IsserSetterBuilderInterface
     */
    public function createIsserSetterBuilderBuilder(UsesBuilderInterface $usesBuilder): IsserSetterBuilderInterface;

    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return ArrayGetterSetterBuilderInterface
     */
    public function createArrayGetterSetterBuilder(UsesBuilderInterface $usesBuilder): ArrayGetterSetterBuilderInterface;
}