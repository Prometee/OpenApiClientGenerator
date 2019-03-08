<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Property;

class ConstantBuilder extends PropertyBuilder
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setScope(static::SCOPE_PUBLIC.' const');
    }

    public function getPhpName(): string
    {
        return strtoupper($this->name);
    }

    public function buildPhpDoc(string &$content): void
    {
        //Nothing to do
    }
}
