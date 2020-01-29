<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object;

class TraitGenerator extends ClassGenerator implements TraitGeneratorInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'trait';
}
