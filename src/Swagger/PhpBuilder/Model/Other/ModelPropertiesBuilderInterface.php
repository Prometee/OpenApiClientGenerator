<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;

interface ModelPropertiesBuilderInterface extends PropertiesBuilderInterface
{
    /**
     * @param string $propertyName
     * @param string[] $types
     * @param string|null $description
     *
     * @return ModelPropertyBuilderInterface
     */
    public function configurePropertyFromSwaggerPropertyDefinition(
        string $propertyName,
        array $types,
        ?string $description = null
    ): ModelPropertyBuilderInterface;
}