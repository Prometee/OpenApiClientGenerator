<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

interface PhpDocFactoryInterface
{
    /**
     * @param UsesGeneratorInterface $usesBuilder
     *
     * @return PhpDocGeneratorInterface
     */
    public function createPhpDocBuilder(UsesGeneratorInterface $usesBuilder): PhpDocGeneratorInterface;

    /**
     * @param string $phpDocBuilderClass
     */
    public function setPhpDocBuilderClass(string $phpDocBuilderClass): void;

    /**
     * @return string
     */
    public function getPhpDocBuilderClass(): string;
}