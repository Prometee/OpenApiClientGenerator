<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

/**
* @param UsesGeneratorInterface $generator
 */
class UsesView extends AbstractArrayView implements UsesViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArrayToBuild(): array
    {
        return $this->generator->getUses();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        $content = parent::build($indent, $eol);

        if (empty($content)) {
            return $content;
        }

        return $content.$this->eol;
    }

    /**
     * {@inheritDoc}
     */
    public function buildArrayItemString($key, string $item): string
    {
        $alias = '';
        if (!empty($item)) {
            $alias = sprintf(' as %s', $item);
        }

        return sprintf('use %s%s;%s',
            $key,
            $alias,
            $this->eol
        );
    }
}