<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

interface PhpDocBuilderInterface extends BuilderInterface
{
    /**
     * @param array|null $types
     *
     * @return string
     */
    public static function getPossibleTypesFromTypeName(?array $types): string;

    /**
     * @param string $line
     * @param int $wrapOn
     *
     * @return array
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

    public function addVarLine(?string $line): void;

    /**
     * @param int $wrapOn
     */
    public function setWrapOn(int $wrapOn): void;

    /**
     * @param string $line
     */
    public function addDescriptionLine(string $line): void;

    public function addEmptyLine(): void;

    public function addParamLine(string $name, string $type = '', string $description = '');
}
