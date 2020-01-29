<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

interface PhpDocAwareGeneratorInterface
{
    /**
     * @return PhpDocGeneratorInterface
     */
    public function getPhpDocBuilder(): PhpDocGeneratorInterface;

    /**
     * @param PhpDocGeneratorInterface $phpDocBuilder
     */
    public function setPhpDocBuilder(PhpDocGeneratorInterface $phpDocBuilder): void;
}
