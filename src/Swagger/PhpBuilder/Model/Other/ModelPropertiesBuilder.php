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
    public function configurePropertyFromSwaggerPropertyDefinition(
        string $propertyName,
        array $types,
        ?string $description = null
    ): ModelPropertyBuilderInterface
    {
        /** @var ModelPropertyBuilderInterface $propertyBuilder */
        $propertyBuilder = $this->classFactory->createPropertyBuilder($this->getUsesBuilder());
        $propertyBuilder->configure($propertyName, $types);
        if (null !== $description) {
            $propertyBuilder->setDescription($description);
        }
        $this->addProperty($propertyBuilder);

        return $propertyBuilder;
    }
}