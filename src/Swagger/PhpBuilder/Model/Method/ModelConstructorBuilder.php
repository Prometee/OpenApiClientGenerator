<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\ConstructorBuilder;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute\ModelPropertyBuilderInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Other\ModelPropertiesBuilderInterface;

class ModelConstructorBuilder extends ConstructorBuilder implements ModelConstructorBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureFromPropertiesBuilder(ModelPropertiesBuilderInterface $modelPropertiesBuilder): void
    {
        /** @var ModelPropertyBuilderInterface $modelPropertyBuilder */
        foreach ($modelPropertiesBuilder->getProperties() as $modelPropertyBuilder) {
            $this->configureParameterFromPropertyBuilder($modelPropertyBuilder);
            $this->configureBodyFromPropertyBuilder($modelPropertyBuilder);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureBodyFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void
    {
        $format = null;
        switch ($modelPropertyBuilder->getPhpType()) {
            case 'array':
                $format = '$this->%1$s = [];';
                break;
            case 'string':
                $format = '$this->%1$s = \'\';';
                break;
            case 'bool':
                $format = '$this->%1$s = true;';
                break;
            case 'int':
                $format = '$this->%1$s = 0;';
                break;
            case 'float':
                $format = '$this->%1$s = .0;';
                break;
        }

        if ($modelPropertyBuilder->isRequired()) {
            $format = '$this->%1$s = $%1$s;';
        }

        if (null !== $format) {
            $this->addLine(sprintf($format, $modelPropertyBuilder->getName()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureParameterFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void
    {
        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $this->getUsesBuilder()
        );
        $methodParameterBuilder->configure(
            $modelPropertyBuilder->getTypes(),
            $modelPropertyBuilder->getName(),
            null,
            false,
            $modelPropertyBuilder->getDescription()
        );
        $this->addParameter($methodParameterBuilder);
    }
}