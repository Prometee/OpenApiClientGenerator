<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Factory;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilderInterface;

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
    public function createPhpDocBuilder(UsesBuilderInterface $usesBuilder): PhpDocBuilderInterface
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