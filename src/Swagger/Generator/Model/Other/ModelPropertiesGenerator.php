<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;

class ModelPropertiesGenerator extends PropertiesGenerator implements ModelPropertiesGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function addPropertyFromSwaggerPropertyDefinition(
        ModelPropertyGeneratorInterface $propertyGenerator,
        string $propertyName,
        array $types,
        bool $required = false,
        bool $inherited = false,
        ?string $description = null
    ): void
    {
        $propertyGenerator->setRequired($required);
        $propertyGenerator->setInherited($inherited);
        $propertyGenerator->configure($propertyName, $types);

        if (null !== $description) {
            $propertyGenerator->setDescription($description);
        }

        $this->addProperty($propertyGenerator);
    }
}