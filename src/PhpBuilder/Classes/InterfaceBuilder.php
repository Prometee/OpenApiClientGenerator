<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes;

class InterfaceBuilder extends ClassBuilder implements InterfaceBuilderInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'interface';
}
