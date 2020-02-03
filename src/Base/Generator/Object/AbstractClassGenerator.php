<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object;

class AbstractClassGenerator extends ClassGenerator implements AbstractClassGeneratorInterface
{
    /** {@inheritDoc} */
    protected $generatorType = 'abstract class';
}
