<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class AbstractClassBuilder extends ClassBuilder
{
    public function __construct(string $namespace, string $className, ?string $extendClassName = null, array $implements = [])
    {
        $this->builderType = static::TYPE_ABSTRACT_CLASS;
        parent::__construct($namespace, $className, $extendClassName, $implements);
    }
}
