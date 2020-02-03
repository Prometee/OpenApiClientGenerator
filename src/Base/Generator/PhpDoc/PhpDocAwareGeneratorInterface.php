<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

interface PhpDocAwareGeneratorInterface
{
    /**
     * @return PhpDocGeneratorInterface
     */
    public function getPhpDocGenerator(): PhpDocGeneratorInterface;

    /**
     * @param PhpDocGeneratorInterface $phpDocGenerator
     */
    public function setPhpDocGenerator(PhpDocGeneratorInterface $phpDocGenerator): void;
}
