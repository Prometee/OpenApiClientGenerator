<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

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

    /**
     * {@inheritdoc}
     */
    public function resetPhpDocBuilder(): void;
}
