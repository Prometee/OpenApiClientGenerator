<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object;

class TraitBuilder extends ClassBuilder implements TraitBuilderInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'trait';
}
