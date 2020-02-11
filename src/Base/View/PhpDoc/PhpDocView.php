<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\PhpDoc;

use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\AbstractView;

/**
 * @property PhpDocGeneratorInterface $generator
 */
class PhpDocView extends AbstractView implements PhpDocViewInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        $phpdocLines = $this->buildLines();

        if (empty($phpdocLines)) {
            return null;
        }

        if ($this->generator->hasSingleVarLine()) {
            return sprintf('%1$s/** %3$s */%2$s', $indent, "\n", $phpdocLines[0]);
        }

        $lines = [];
        foreach ($phpdocLines as $phpdocLine) {
            $lines[] = sprintf('%s * %s', $indent,$phpdocLine);
        }

        return sprintf('%1$s/**%2$s%3$s%2$s%1$s */%2$s', $indent, "\n", implode("\n", $lines));
    }

    /**
     * {@inheritDoc}
     */
    public function buildLines(): array
    {
        $phpdocLines = [];
        $previousType = null;

        $this->orderLines();

        foreach ($this->generator->getLines() as $type => $lines) {
            if ($previousType === null) {
                $previousType = $type;
            }
            if ($previousType !== $type) {
                $phpdocLines[] = '';
                $previousType = $type;
            }
            $phpdocLines = array_merge(
                $phpdocLines,
                $this->buildTypedLines($type, $lines)
            );
        }
        return $phpdocLines;
    }

    /**
     * {@inheritDoc}
     */
    public function buildTypedLines(string $type, array $lines): array
    {
        $phpdocLines = [];
        $linePrefix = $this->buildTypedLinePrefix($type);

        foreach ($lines as $line) {
            $phpdocLines = array_merge(
                $phpdocLines,
                $this->buildLinesFromSingleLine($linePrefix, $line)
            );
        }

        return $phpdocLines;
    }

    /**
     * {@inheritDoc}
     */
    public function buildTypedLinePrefix(string $type): string
    {
        if ($type === PhpDocGeneratorInterface::TYPE_DESCRIPTION) {
            return '';
        }

        return sprintf('@%s ', $type);
    }

    /**
     * {@inheritDoc}
     */
    public function buildLinesFromSingleLine(string $linePrefix, string $line): array
    {
        $lines = [];
        $linePrefixLength = strlen($linePrefix);
        $blankSubLinePrefix = str_repeat(' ', $linePrefixLength);
        $explodedLines = explode("\n", $line);

        foreach ($explodedLines as $i=>$explodedLine) {
            $wrapOn = $this->generator->getWrapOn();
            if ($i === 0) {
                $wrapOn -= $linePrefixLength;
            }

            $lines = array_merge(
                $lines,
                $this->wrapLines($explodedLine, $wrapOn)
            );
        }

        foreach ($lines as $i=>$line) {
            $subLinePrefix = $i === 0 ? $linePrefix : $blankSubLinePrefix;
            $lines[$i] = $subLinePrefix . $line;
        }

        return $lines;
    }

    /**
     * {@inheritDoc}
     */
    public function wrapLines(string $line, ?int $wrapOn = null): array
    {
        $wrapOn = $wrapOn ?? $this->generator->getWrapOn();
        $lines = [];
        $currentLine = '';

        foreach (explode(' ', $line) as $word) {
            if (iconv_strlen($currentLine . ' ' . $word) > $wrapOn) {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine .= (!empty($currentLine) ? ' ' : '') . $word;
            }
        }
        $lines[] = $currentLine;

        return $lines;
    }

    /**
     * {@inheritDoc}
     */
    public function orderLines(): void
    {
        $this->generator->orderLines(function ($k1, $k2) {
            $o1 = array_search($k1, PhpDocGeneratorInterface::LINE_TYPE_ORDER);
            $o2 = array_search($k2, PhpDocGeneratorInterface::LINE_TYPE_ORDER);

            return $o1 - $o2;
        });
    }
}