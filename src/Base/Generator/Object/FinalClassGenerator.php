<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object;

class FinalClassGenerator extends ClassGenerator implements FinalClassGeneratorInterface
{
    /** {@inheritDoc} */
    protected $generatorType = 'final class';
}
