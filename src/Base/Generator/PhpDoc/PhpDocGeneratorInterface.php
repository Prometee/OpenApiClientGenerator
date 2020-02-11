<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;

interface PhpDocGeneratorInterface extends GeneratorInterface
{
    public const DEFAULT_WRAP_ON = 110;
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
     * @param int|null $wrapOn
     *
     * @return mixed
     */
    public function configure(array $lines = [], ?int $wrapOn = null): void;

    /**
     * @param callable $orderingCallable
     */
    public function orderLines(callable $orderingCallable): void;

    /**
     * @return bool
     */
    public function hasSingleVarLine(): bool;

    /**
     * @param string $line
     */
    public function addReturnLine(string $line): void;

    /**
     * @param string $line
     */
    public function addThrowsLine(string $line): void;

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
     * @return string[]
     */
    public function getLines(): array;

    /**
     * @param string[] $lines
     */
    public function setLines(array $lines): void;
}
