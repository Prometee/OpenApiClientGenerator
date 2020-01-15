<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes;

class AbstractClassBuilder extends ClassBuilder implements AbstractClassBuilderInterface
{
    /** {@inheritDoc} */
    protected $builderType = 'abstract class';
}
