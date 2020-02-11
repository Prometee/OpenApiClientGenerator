<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

abstract class AbstractGenerator implements GeneratorInterface
{
    /** @var ViewInterface */
    protected $view;

    /**
     * {@inheritDoc}
     */
    public function getView(): ViewInterface
    {
        return $this->view;
    }

    /**
     * {@inheritDoc}
     */
    public function setView(ViewInterface $view): void
    {
        $view->setGenerator($this);
        $this->view = $view;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null, string $eol = null): ?string
    {
        return $this->view->build($indent, $eol);
    }

    public function __clone()
    {
        $this->setView(clone $this->view);
    }
}