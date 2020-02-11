<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

interface GeneratorInterface
{
    /**
     * @param string|null $indent
     * @param string|null $eol
     *
     * @return string|null
     */
    public function generate(string $indent = null, string $eol = null): ?string;

    /**
     * @return ViewInterface
     */
    public function getView(): ViewInterface;

    /**
     * @param ViewInterface $view
     */
    public function setView(ViewInterface $view): void;
}
