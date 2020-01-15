<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes;

class FinalClassBuilder extends ClassBuilder implements FinalClassBuilderInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'final class';
}
