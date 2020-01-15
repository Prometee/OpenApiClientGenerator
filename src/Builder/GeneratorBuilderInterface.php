<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Builder;

use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;

interface GeneratorBuilderInterface
{
    /**
     * @return GeneratorInterface
     */
    public function build(): GeneratorInterface;

    /**
     * @return ClassFactoryInterface
     */
    public function getClassFactory(): ClassFactoryInterface;

    /**
     * @param ClassFactoryInterface $classFactory
     */
    public function setClassFactory(ClassFactoryInterface $classFactory): void;
}