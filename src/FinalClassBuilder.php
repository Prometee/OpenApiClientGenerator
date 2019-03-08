<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class FinalClassBuilder extends ClassBuilder
{
    public function __construct(string $namespace, string $className, ?string $extendClassName = null, array $implements = [])
    {
        $this->builderType = static::TYPE_FINALE_CLASS;
        parent::__construct($namespace, $className, $extendClassName, $implements);
    }
}
