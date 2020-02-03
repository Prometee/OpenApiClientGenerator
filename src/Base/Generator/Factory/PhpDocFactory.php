<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

class PhpDocFactory implements PhpDocFactoryInterface
{
    /** @var string */
    protected $phpDocGeneratorClass;

    /**
     * @param string $phpDocGeneratorClass
     */
    public function __construct(string $phpDocGeneratorClass)
    {
        $this->phpDocGeneratorClass = $phpDocGeneratorClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createPhpDocGenerator(UsesGeneratorInterface $usesGenerator): PhpDocGeneratorInterface
    {
        return new $this->phpDocGeneratorClass($usesGenerator);
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