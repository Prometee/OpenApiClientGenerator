<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilderInterface;

interface PhpDocFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PhpDocBuilderInterface
     */
    public function createPhpDocBuilder(UsesBuilderInterface $usesBuilder): PhpDocBuilderInterface;
}