<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\TraitsGeneratorInterface;

/**
 * @property TraitsGeneratorInterface $generator
 */
class TraitsView extends AbstractArrayView implements TraitsViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArrayToBuild(): array
    {
        return $this->generator->getTraits();
    }

    /**
     * {@inheritDoc}
     */
    public function buildArrayItemString($key, string $item): string
    {
        $prefix = '%2$s%2$s';
        $suffix = ',%1$s';
        if ($key === array_key_first($this->getArrayToBuild())) {
            $prefix = '%2$suse ';
        }
        if ($key === array_key_last($this->getArrayToBuild())) {
            $suffix = ';%1$s%1$s';
        }
        $format = sprintf('%s%s%s', $prefix, '%3$s', $suffix);
        return sprintf($format, $this->eol, $this->indent, $item);
    }
}