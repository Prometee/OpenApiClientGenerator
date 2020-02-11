<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

interface ViewInterface
{
    /**
     * @param string|null $indent
     * @param string|null $eol
     *
     * @return string|null
     */
    public function build(string $indent = null, string $eol = null): ?string;

    /**
     * @param string $eol
     */
    public function setEol(string $eol): void;

    /**
     * @param string $indent
     */
    public function setIndent(string $indent): void;

    /**
     * @return string
     */
    public function getEol(): string;

    /**
     * @return string
     */
    public function getIndent(): string;

    /**
     * @return GeneratorInterface|null
     */
    public function getGenerator(): ?GeneratorInterface;

    /**
     * @param GeneratorInterface|null $generator
     */
    public function setGenerator(?GeneratorInterface $generator): void;
}