<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocAwareBuilderInterface;

interface PropertyBuilderInterface extends BuilderInterface, PhpDocAwareBuilderInterface
{
    /**
     * @param string $name
     * @param array $types
     * @param string|null $value
     * @param string $description
     */
    public function configure(
        string $name,
        array $types = [],
        ?string $value = null,
        string $description = ''
    );

    /**
     * @return string
     */
    public function getScope(): string;

    /**
     * @param string $scope
     */
    public function setScope(string $scope): void;

    /**
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPhpName(): string;

    /**
     * @return string|null
     */
    public function getPhpType(): ?string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return string[]
     */
    public function getTypes(): array;

    /**
     * @param string[] $types
     */
    public function setTypes(array $types): void;

    /**
     * @param string $type
     */
    public function addType(string $type): void;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasType(string $type): bool;

    /**
     * @return string|null
     */
    public function getType(): ?string;
}
