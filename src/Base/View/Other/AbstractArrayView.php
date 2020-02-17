<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\AbstractView;

abstract class AbstractArrayView extends AbstractView implements ArrayViewInterface
{
    /**
     * {@inheritDoc}
     */
    abstract public function getArrayToBuild(): array;

    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        parent::build($indent, $eol);

        if (count($this->getArrayToBuild()) === 0) {
            return null;
        }

        $content = '';
        foreach ($this->getArrayToBuild() as $key => $item) {
            $content .= $this->buildArrayItem($key, $item);
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function buildArrayItem($key, $item): ?string
    {
        if ($item instanceof GeneratorInterface) {
            return $item->generate($this->indent, $this->eol);
        }

        return $this->buildArrayItemString($key, $item);
    }

    /**
     * {@inheritDoc}
     */
    public function buildArrayItemString($key, string $item): string
    {
        return $item;
    }
}