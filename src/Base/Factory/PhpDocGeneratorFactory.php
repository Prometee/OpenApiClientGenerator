<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

class PhpDocGeneratorFactory implements PhpDocGeneratorFactoryInterface
{
    /** @var PhpDocViewFactoryInterface */
    protected $phpDocViewFactory;
    /** @var string */
    protected $phpDocGeneratorClass;

    /**
     * @param PhpDocViewFactoryInterface $phpDocViewFactory
     */
    public function __construct(PhpDocViewFactoryInterface $phpDocViewFactory)
    {
        $this->phpDocViewFactory = $phpDocViewFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createPhpDocGenerator(): PhpDocGeneratorInterface
    {
        return new $this->phpDocGeneratorClass(
            $this->phpDocViewFactory->createPhpDocViewFactory()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpDocGeneratorClass(): string
    {
        return $this->phpDocGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPhpDocGeneratorClass(string $phpDocGeneratorClass): void
    {
        $this->phpDocGeneratorClass = $phpDocGeneratorClass;
    }
}