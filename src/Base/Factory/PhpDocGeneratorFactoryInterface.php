<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

interface PhpDocGeneratorFactoryInterface
{
    /**
     * @return PhpDocGeneratorInterface
     */
    public function createPhpDocGenerator(): PhpDocGeneratorInterface;

    /**
     * @param string $phpDocGeneratorClass
     */
    public function setPhpDocGeneratorClass(string $phpDocGeneratorClass): void;

    /**
     * @return string
     */
    public function getPhpDocGeneratorClass(): string;
}