<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGeneratorInterface;

/**
 * @property PropertiesGeneratorInterface $generator
 */
class PropertiesView extends AbstractArrayView implements PropertiesViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArrayToBuild(): array
    {
        return $this->generator->getProperties();
    }
}