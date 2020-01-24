<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;

interface ModelPropertiesBuilderInterface extends PropertiesBuilderInterface
{
    /**
     * @param ModelPropertyBuilderInterface $propertyBuilder
     * @param string $propertyName
     * @param string[] $types
     * @param bool $required
     * @param bool $inherited
     * @param string|null $description
     */
    public function addPropertyFromSwaggerPropertyDefinition(
        ModelPropertyBuilderInterface $propertyBuilder,
        string $propertyName,
        array $types,
        bool $required = false,
        bool $inherited = false,
        ?string $description = null
    ): void;
}