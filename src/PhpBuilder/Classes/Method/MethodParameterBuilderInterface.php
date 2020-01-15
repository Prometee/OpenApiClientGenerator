<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;

interface MethodParameterBuilderInterface extends BuilderInterface
{
    /**
     * @param string|null $type
     * @param string $name
     * @param string|null $value
     * @param bool $byReference
     * @param string|null $description
     */
    public function configure(
        ?string $type,
        string $name,
        ?string $value = null,
        bool $byReference = false,
        string $description = ''
    ):void;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return bool
     */
    public function isByReference(): bool;

    /**
     * @return array|null
     */
    public function getTypes(): ?array;

    /**
     * @return string
     */
    public function getName(): string;

    public function getValueType();

    /**
     * @param bool $byReference
     */
    public function setByReference(bool $byReference): void;

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void;

    /**
     * @return string
     */
    public function getPhpName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * @return string|null
     */
    public function getPhpType(): ?string;

    /**
     * @return string|null
     */
    public function getValue(): ?string;
}