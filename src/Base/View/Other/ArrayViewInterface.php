<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Other;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

interface ArrayViewInterface extends ViewInterface
{
    /**
     * @return ViewInterface[]|string[]
     */
    public function getArrayToBuild(): array;

    /**
     * @param string|int $key
     * @param string $item
     *
     * @return string
     */
    public function buildArrayItemString($key, string $item): string;

    /**
     * @param string|int $key
     * @param ViewInterface|string $item
     *
     * @return string
     */
    public function buildArrayItem($key, $item): string;
}