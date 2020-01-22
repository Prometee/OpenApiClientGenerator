<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilderInterface;

interface PhpDocFactoryInterface
{
    /**
     * @param UsesBuilderInterface $usesBuilder
     *
     * @return PhpDocBuilderInterface
     */
    public function createPhpDocBuilder(UsesBuilderInterface $usesBuilder): PhpDocBuilderInterface;

    /**
     * @param string $phpDocBuilderClass
     */
    public function setPhpDocBuilderClass(string $phpDocBuilderClass): void;

    /**
     * @return string
     */
    public function getPhpDocBuilderClass(): string;
}