<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc;

interface PhpDocAwareBuilderInterface
{
    /**
     * @return PhpDocBuilderInterface
     */
    public function getPhpDocBuilder(): PhpDocBuilderInterface;

    /**
     * @param PhpDocBuilderInterface $phpDocBuilder
     */
    public function setPhpDocBuilder(PhpDocBuilderInterface $phpDocBuilder): void;
}
