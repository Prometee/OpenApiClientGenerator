<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Method;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

interface MethodViewInterface extends ViewInterface
{
    /**
     * @param string|null $indent
     *
     * @return string
     */
    public function buildMethodBody(string $indent = null): string;

    /**
     * @param string|null $indent
     *
     * @return string
     */
    public function buildMethodSignature(string $indent = null): string;

    /**
     * @param string|null $indent
     * @param string $formatVar
     *
     * @return string
     */
    public function buildMethodParameters(string $indent = null, string $formatVar = ' '): string;

    /**
     * @param string|null $indent
     *
     * @return string
     */
    public function buildReturnType(string $indent = null): string;
}