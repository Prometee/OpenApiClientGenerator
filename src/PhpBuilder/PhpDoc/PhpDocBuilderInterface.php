<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;

interface PhpDocBuilderInterface extends BuilderInterface
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
     * @param string[] $lines
     * @param int $wrapOn
     */
    public function configure(array $lines = [], int $wrapOn = 100);

    /**
     * @param string[]|null $types
     *
     * @return string
     */
    public static function getPossibleTypesFromTypeName(?array $types): string;

    /**
     * @param string $line
     * @param int $wrapOn
     *
     * @return string[]
     */
    public static function wrapLines(string $line, int $wrapOn = 110): array;

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
