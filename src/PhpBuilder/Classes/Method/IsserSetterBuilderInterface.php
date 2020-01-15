<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

interface IsserSetterBuilderInterface extends GetterSetterBuilderInterface
{
    public const ISSER_PREFIX = 'is';

    /**
     * @param string|null $indent
     */
    public function configureGetter(string $indent = null): void;
}