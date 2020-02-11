<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

abstract class AbstractView implements ViewInterface
{
    /** @var string */
    protected $indent = '    ';

    /** @var string */
    protected $eol = PHP_EOL;

    /** @var GeneratorInterface|null */
    protected $generator;

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        if (null === $this->generator) {
            throw new Exception('You must set a generator before building this view !');
        }
        $this->indent = $indent ?? $this->indent;
        $this->eol = $eol ?? $this->eol;

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getEol(): string
    {
        return $this->eol;
    }

    /**
     * {@inheritDoc}
     */
    public function setEol(string $eol): void
    {
        $this->eol = $eol;
    }

    /**
     * {@inheritDoc}
     */
    public function getIndent(): string
    {
        return $this->indent;
    }

    /**
     * {@inheritDoc}
     */
    public function setIndent(string $indent): void
    {
        $this->indent = $indent;
    }

    /**
     * {@inheritDoc}
     */
    public function getGenerator(): ?GeneratorInterface
    {
        return $this->generator;
    }

    /**
     * {@inheritDoc}
     */
    public function setGenerator(?GeneratorInterface $generator): void
    {
        $this->generator = $generator;
    }
}