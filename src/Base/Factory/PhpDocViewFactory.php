<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\PhpDoc\PhpDocViewInterface;

class PhpDocViewFactory implements PhpDocViewFactoryInterface
{
    /** @var string */
    protected $phpDocViewClass;

    /**
     * {@inheritDoc}
     */
    public function createPhpDocViewFactory(): PhpDocViewInterface
    {
        return new $this->phpDocViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhpDocViewClass(string $phpDocViewClass): void
    {
        $this->phpDocViewClass = $phpDocViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpDocViewClass(): string
    {
        return $this->phpDocViewClass;
    }
}