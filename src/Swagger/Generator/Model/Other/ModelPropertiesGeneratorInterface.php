<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;

interface ModelPropertiesGeneratorInterface extends PropertiesGeneratorInterface
{
    /**
     * @param ModelPropertyGeneratorInterface $propertyGenerator
     * @param string $propertyName
     * @param string[] $types
     * @param bool $required
     * @param bool $inherited
     * @param string|null $description
     */
    public function addPropertyFromSwaggerPropertyDefinition(
        ModelPropertyGeneratorInterface $propertyGenerator,
        string $propertyName,
        array $types,
        bool $required = false,
        bool $inherited = false,
        ?string $description = null
    ): void;
}