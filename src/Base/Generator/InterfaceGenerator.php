<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

class InterfaceGenerator extends ClassGenerator implements InterfaceGeneratorInterface
{
    /** {@inheritDoc} */
    protected $generatorType = 'interface';
}
