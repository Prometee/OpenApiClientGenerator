<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

class PhpDocGenerator implements PhpDocGeneratorInterface
{
    /** @var array */
    protected $lines = [];
    /** @var int */
    protected $wrapOn = 100;

    /**
     * {@inheritDoc}
     */
    public function configure(array $lines = [], int $wrapOn = 100)
    {
        $this->lines = $lines;
        $this->wrapOn = $wrapOn;
    }

    /**
     * {@inheritDoc}
     */
    public function addLine(string $line, string $type = ''): void
    {
        if (!isset($this->lines[$type])) {
            $this->lines[$type] = [];
        }

        $this->lines[$type][] = $line;
    }

    /**
     * {@inheritDoc}
     */
    public function addDescriptionLine(string $line): void
    {
        $this->addLine($line, static::TYPE_DESCRIPTION);
    }

    /**
     * {@inheritDoc}
     */
    public function addEmptyLine(): void
    {
        $this->addDescriptionLine('');
    }

    /**
     * {@inheritDoc}
     */
    public function addVarLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_VAR);
    }

    /**
     * {@inheritDoc}
     */
    public function addParamLine(string $name, string $type = '', string $description = ''): void
    {
        $this->addLine(
            (empty($type) ? '' : $type . ' ') . $name . (empty($description) ? '' : ' ' . $description),
            static::TYPE_PARAM
        );
    }

    /**
     * {@inheritDoc}
     */
    public function addReturnLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_RETURN);
    }

    /**
     * {@inheritDoc}
     */
    public function addThrowsLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_THROWS);
    }

    /**
     * {@inheritDoc}
     */
    public function hasSingleVarLine(): bool
    {
        return isset($this->lines[static::TYPE_VAR])
            && count($this->lines) === 1
            && count($this->lines[static::TYPE_VAR]) === 1;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null): ?string
    {
        $phpdocLines = $this->buildLines();

        if (empty($phpdocLines)) {
            return null;
        }

        if ($this->hasSingleVarLine()) {
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

        foreach ($this->lines as $type => $lines) {
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
        if ($type === static::TYPE_DESCRIPTION) {
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
            $wrapOn = $this->wrapOn;
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
        $wrapOn = $wrapOn ?? $this->wrapOn;
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
    public static function getPossibleTypesFromTypeNames(array $types = []): string
    {
        if (empty($types)) {
            return '';
        }
        $typesFound = [];

        foreach ($types as $type) {
            if ($type === null) {
                continue;
            }
            if (preg_match('#^\?#', $type)) {
                $typesFound[] = 'null';
            }
            $typesFound[] = ltrim($type, '?');
        }

        $typesFound = array_unique($typesFound);

        return implode('|', $typesFound);
    }

    /**
     * {@inheritDoc}
     */
    public function orderLines(): void
    {
        uksort($this->lines, function ($k1, $k2) {
            $o1 = array_search($k1, static::LINE_TYPE_ORDER);
            $o2 = array_search($k2, static::LINE_TYPE_ORDER);

            return $o1 - $o2;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function setWrapOn(int $wrapOn): void
    {
        $this->wrapOn = $wrapOn;
    }

    /**
     * {@inheritDoc}
     */
    public function getWrapOn(): int
    {
        return $this->wrapOn;
    }
}
