<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

interface GeneratorInterface
{
    /**
     * @param string|null $indent
     *
     * @return string|null
     */
    public function generate(string $indent = null): ?string;
}
