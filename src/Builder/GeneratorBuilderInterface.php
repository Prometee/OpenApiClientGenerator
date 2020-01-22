<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Builder;

use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\PhpDocFactoryInterface;

interface GeneratorBuilderInterface
{
    /**
     * @return GeneratorInterface
     */
    public function build(): GeneratorInterface;

    /**
     * @return PhpDocFactoryInterface
     */
    public function createPhpDocFactory(): PhpDocFactoryInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return ClassFactoryInterface
     */
    public function createClassFactory(PhpDocFactoryInterface $phpDocFactory): ClassFactoryInterface;

    /**
     * @param PhpDocFactoryInterface $phpDocFactory
     *
     * @return MethodFactoryInterface
     */
    public function createMethodFactory(PhpDocFactoryInterface $phpDocFactory): MethodFactoryInterface;
}