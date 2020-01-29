<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Factory;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

class PhpDocFactory implements PhpDocFactoryInterface
{
    /** @var string */
    protected $phpDocBuilderClass;

    /**
     * @param string $phpDocBuilderClass
     */
    public function __construct(string $phpDocBuilderClass)
    {
        $this->phpDocBuilderClass = $phpDocBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createPhpDocBuilder(UsesGeneratorInterface $usesBuilder): PhpDocGeneratorInterface
    {
        return new $this->phpDocBuilderClass($usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpDocBuilderClass(): string
    {
        return $this->phpDocBuilderClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPhpDocBuilderClass(string $phpDocBuilderClass): void
    {
        $this->phpDocBuilderClass = $phpDocBuilderClass;
    }
}