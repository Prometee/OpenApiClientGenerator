<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;

class ModelPropertiesGenerator extends PropertiesGenerator implements ModelPropertiesGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function addPropertyFromSwaggerPropertyDefinition(
        ModelPropertyGeneratorInterface $propertyBuilder,
        string $propertyName,
        array $types,
        bool $required = false,
        bool $inherited = false,
        ?string $description = null
    ): void
    {
        $propertyBuilder->setRequired($required);
        $propertyBuilder->setInherited($inherited);
        $propertyBuilder->configure($propertyName, $types);

        if (null !== $description) {
            $propertyBuilder->setDescription($description);
        }

        $this->addProperty($propertyBuilder);
    }
}