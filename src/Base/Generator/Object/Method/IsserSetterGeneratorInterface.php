<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

interface IsserSetterGeneratorInterface extends GetterSetterGeneratorInterface
{
    public const ISSER_PREFIX = 'is';

    /**
     * @param string|null $indent
     */
    public function configureGetter(string $indent = null): void;
}