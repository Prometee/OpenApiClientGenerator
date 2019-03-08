<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

interface BuilderInterface
{
    public function build(string $indent = null): ?string;
}
