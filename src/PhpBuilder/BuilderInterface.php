<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder;

interface BuilderInterface
{
    /**
     * @param string|null $indent
     *
     * @return string|null
     */
    public function build(string $indent = null): ?string;
}
