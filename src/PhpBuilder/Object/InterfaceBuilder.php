<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object;

class InterfaceBuilder extends ClassBuilder implements InterfaceBuilderInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'interface';
}
