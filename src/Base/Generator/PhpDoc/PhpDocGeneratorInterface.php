<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

interface PhpDocGeneratorInterface extends GeneratorInterface
{
    public const TYPE_DESCRIPTION = 'description';
    public const TYPE_VAR = 'var';
    public const TYPE_RETURN = 'return';
    public const TYPE_PARAM = 'param';
    public const TYPE_THROWS = 'throws';

    public const LINE_TYPE_ORDER = [
        self::TYPE_DESCRIPTION,
        self::TYPE_VAR,
        self::TYPE_PARAM,
        self::TYPE_RETURN,
        self::TYPE_THROWS,
    ];

    /**
     * @param array $lines
     * @param int $wrapOn
     *
     * @return mixed
     */
    public function configure(array $lines = [], int $wrapOn = 100);

    /**
     * @param string[] $types
     *
     * @return string
     */
    public static function getPossibleTypesFromTypeNames(array $types = []): string;

    /**
     * @return string[]
     */
    public function buildLines(): array;

    /**
     * @param string $type
     * @param string[] $lines
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
     * @return bool
     */
    public function hasSingleVarLine(): bool;

    /**
     * @param string|null $line
     */
    public function addReturnLine(?string $line): void;

    /**
     * @param string|null $line
     */
    public function addThrowsLine(?string $line): void;

    /**
     * @return int
     */
    public function getWrapOn(): int;

    /**
     * @param string $line
     * @param string $type
     */
    public function addLine(string $line, string $type = ''): void;

    /**
     * @param string|null $line
     */
    public function addVarLine(?string $line): void;

    /**
     * @param int $wrapOn
     */
    public function setWrapOn(int $wrapOn): void;

    /**
     * @param string $line
     */
    public function addDescriptionLine(string $line): void;

    /**
     * Add an empty description line
     */
    public function addEmptyLine(): void;

    /**
     * @param string $name
     * @param string $type
     * @param string $description
     */
    public function addParamLine(string $name, string $type = '', string $description = ''): void;

    /**
     * Order PHPDoc line types
     */
    public function orderLines(): void;
}
