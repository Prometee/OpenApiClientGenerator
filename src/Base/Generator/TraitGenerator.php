<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

class TraitGenerator extends ClassGenerator implements TraitGeneratorInterface
{
    /** {@inheritDoc} */
    protected $generatorType = 'trait';
}
