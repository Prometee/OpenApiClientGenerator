<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class TraitBuilder extends ClassBuilder
{
    public function __construct(string $namespace, string $className, ?string $extendClassName = null, array $implements = [])
    {
        $this->builderType = static::TYPE_TRAIT;
        parent::__construct($namespace, $className, $extendClassName, $implements);
    }
}
