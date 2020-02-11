<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\PhpDoc\PhpDocViewInterface;

interface PhpDocViewFactoryInterface
{
    /**
     * @return PhpDocViewInterface
     */
    public function createPhpDocViewFactory(): PhpDocViewInterface;

    /**
     * @param string $phpDocViewClass
     */
    public function setPhpDocViewClass(string $phpDocViewClass): void;

    /**
     * @return string
     */
    public function getPhpDocViewClass(): string;
}