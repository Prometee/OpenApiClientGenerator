<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc;

class PhpDocBuilder implements PhpDocBuilderInterface
{
    /** @var string[] */
    protected $lines = [];
    /** @var int */
    protected $wrapOn = 100;

    /**
     * @param string[] $lines
     * @param int $wrapOn
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
    public function build(string $indent = null): ?string
    {
        $content = '';

        $phpdocLines = [];
        $previousType = null;
        $this->orderLines();
        foreach ($this->lines as $type => $lines) {
            if ($previousType !== $type) {
                if ($previousType !== null) {
                    $phpdocLines[] = '';
                }
                $previousType = $type;
            }
            foreach ($lines as $line) {
                $lineSuffix = ($type === static::TYPE_DESCRIPTION ? '' : '@' . $type . ' ');
                foreach (static::wrapLines($lineSuffix . $line, $this->wrapOn) as $i => $l) {
                    $phpdocLines[] = ($i > 0 ? str_repeat(' ', strlen($lineSuffix)) : '') . $l;
                }
            }
        }

        if (!empty($phpdocLines)) {
            if ($this->hasSingleVarLine()) {
                $content .= $indent . '/** ';
                $content .= $phpdocLines[0];
            } else {
                $content .= $indent . '/**' . "\n";
                foreach ($phpdocLines as $phpdocLine) {
                    $content .= $indent . ' * ' . $phpdocLine . "\n";
                }
                $content .= $indent;
            }

            $content .= ' */' . "\n";
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public static function wrapLines(string $line, int $wrapOn = 100): array
    {
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
    public static function getPossibleTypesFromTypeName(?array $types): string
    {
        if ($types === null) {
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
}
