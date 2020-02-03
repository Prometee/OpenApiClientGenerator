<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

interface PhpDocFactoryInterface
{
    /**
     * @param UsesGeneratorInterface $usesGenerator
     *
     * @return PhpDocGeneratorInterface
     */
    public function createPhpDocGenerator(UsesGeneratorInterface $usesGenerator): PhpDocGeneratorInterface;

    /**
     * @param string $phpDocGeneratorClass
     */
    public function setPhpDocGeneratorClass(string $phpDocGeneratorClass): void;

    /**
     * @return string
     */
    public function getPhpDocGeneratorClass(): string;
}