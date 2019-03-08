<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

class PhpDocBuilder implements PhpDocBuilderInterface
{
    public const TYPE_DESCRIPTION = 'description';
    public const TYPE_VAR = 'var';
    public const TYPE_RETURN = 'return';
    public const TYPE_PARAM = 'param';
    public const TYPE_THROWS = 'throws';

    /** @var array */
    protected $lines;

    /** @var int */
    protected $wrapOn;

    /**
     * @param array $lines
     * @param int $wrapOn
     */
    public function __construct(array $lines = [], int $wrapOn = 100)
    {
        $this->lines = $lines;
        $this->wrapOn = $wrapOn;
    }

    /**
     * @param string $line
     * @param string $type
     */
    public function addLine(string $line, string $type = ''): void
    {
        if (!isset($this->lines[$type])) {
            $this->lines[$type] = [];
        }
        $this->lines[$type][] = $line;
    }

    /**
     * @param string $line
     */
    public function addDescriptionLine(string $line): void
    {
        $this->addLine($line, static::TYPE_DESCRIPTION);
    }

    public function addEmptyLine(): void
    {
        $this->addDescriptionLine('');
    }

    public function addVarLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_VAR);
    }

    public function addParamLine(string $name, string $type = '', string $description = '')
    {
        $this->addLine(
            (empty($type) ? '' : $type.' ').$name.(empty($description) ? '' : ' '.$description),
            static::TYPE_PARAM
        );
    }

    public function addReturnLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_RETURN);
    }

    public function addThrowsLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_THROWS);
    }

    /**
     * @return bool
     */
    public function hasSingleVarLine(): bool
    {
        return isset($this->lines[static::TYPE_VAR])
            && count($this->lines) === 1
            && count($this->lines[static::TYPE_VAR]) === 1;
    }

    public function build(string $indent = null): ?string
    {
        $content = '';

        $phpdocLines = [];
        $previousType = null;
        $this->orderLines();
        foreach ($this->lines as $type=>$lines) {
            if ($previousType !== $type) {
                if($previousType !== null) {
                    $phpdocLines[] = '';
                }
                $previousType = $type;
            }
            foreach ($lines as $line) {
                $lineSuffix = ($type === static::TYPE_DESCRIPTION ? '' : '@'.$type.' ');
                foreach (static::wrapLines($lineSuffix.$line, $this->wrapOn) as $i=> $line) {
                    $phpdocLines[] = ($i > 0 ? str_repeat(' ', strlen($lineSuffix)) : '').$line;
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
     * @param string $line
     * @param int $wrapOn
     * @return array
     */
    public static function wrapLines(string $line, int $wrapOn = 100): array
    {
        $lines = [];
        $currentLine = '';
        foreach (explode(' ', $line) as $word) {
            if (iconv_strlen($currentLine.' '.$word) > $wrapOn) {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine .= (!empty($currentLine) ? ' ' : '').$word;
            }
        }
        $lines[] = $currentLine;

        return $lines;
    }

    /**
     * @param array|null $types
     * @return string
     */
    public static function getPossibleTypesFromTypeName(?array $types): string
    {
        if ($types === null) return '';
        $typesFound = [];

        foreach ($types as $type) {
            if ($type === null) continue;
            if (preg_match('#^\?#', $type)) {
                $typesFound[] = 'null';
            }
            $typesFound[] = ltrim($type, '?');
        }

        $typesFound = array_unique($typesFound);

        return implode('|', $typesFound);
    }

    /**
     * @param int $wrapOn
     */
    public function setWrapOn(int $wrapOn): void
    {
        $this->wrapOn = $wrapOn;
    }

    /**
     * @return int
     */
    public function getWrapOn(): int
    {
        return $this->wrapOn;
    }

    public function orderLines()
    {
        $lineTypeOrder = [
            static::TYPE_DESCRIPTION,
            static::TYPE_VAR,
            static::TYPE_PARAM,
            static::TYPE_RETURN,
            static::TYPE_THROWS,
        ];
        uksort($this->lines, function($k1, $k2) use ($lineTypeOrder) {
            $o1 = array_search($k1, $lineTypeOrder);
            $o2 = array_search($k2, $lineTypeOrder);

            return $o1-$o2;
        });
    }
}
