<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Builder;

use Prometee\SwaggerClientGenerator\Base\Generator\Factory\ClassFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Factory\PhpDocFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

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