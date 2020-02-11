<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;

/**
 * @property MethodsGeneratorInterface $generator
 */
class MethodsView extends AbstractArrayView implements MethodsViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArrayToBuild(): array
    {
        return $this->generator->getMethods();
    }
}