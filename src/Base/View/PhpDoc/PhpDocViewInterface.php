<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\PhpDoc;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

interface PhpDocViewInterface extends ViewInterface
{
    /**
     * @return string[]
     */
    public function buildLines(): array;

    /**
     * @param string $type
     * @param array $lines
     *
     * @return string[]
     */
    public function buildTypedLines(string $type, array $lines): array;

    /**
     * @param string $type
     *
     * @return string
     */
    public function buildTypedLinePrefix(string $type): string;

    /**
     * @param string $linePrefix
     * @param string $line
     *
     * @return string[]
     */
    public function buildLinesFromSingleLine(string $linePrefix, string $line): array;

    /**
     * @param string $line
     * @param int|null $wrapOn
     *
     * @return string[]
     */
    public function wrapLines(string $line, ?int $wrapOn = null): array;

    /**
     * Order doc lines
     */
    public function orderLines(): void;
}