<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilder;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;

class ModelPropertiesBuilder extends PropertiesBuilder implements ModelPropertiesBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function addPropertyFromSwaggerPropertyDefinition(
        ModelPropertyBuilderInterface $propertyBuilder,
        string $propertyName,
        array $types,
        bool $required = false,
        bool $inherited = false,
        ?string $description = null
    ): void
    {
        $propertyBuilder->configure($propertyName, $types);
        $propertyBuilder->setRequired($required);
        $propertyBuilder->setInherited($inherited);

        if (null !== $description) {
            $propertyBuilder->setDescription($description);
        }

        $this->addProperty($propertyBuilder);
    }
}