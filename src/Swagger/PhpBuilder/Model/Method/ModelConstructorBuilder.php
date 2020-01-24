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
        $inheritedRequiredProperties = [];
        /** @var ModelPropertyBuilderInterface $modelPropertyBuilder */
        foreach ($modelPropertiesBuilder->getProperties() as $modelPropertyBuilder) {
            $this->configureParameterFromPropertyBuilder($modelPropertyBuilder);
            $this->configureBodyFromPropertyBuilder($modelPropertyBuilder);
            if (false === $modelPropertyBuilder->isInherited()) {
                continue;
            }
            if (false === $modelPropertyBuilder->isRequired()) {
                continue;
            }
            $inheritedRequiredProperties[] = $modelPropertyBuilder->getPhpName();
        }

        if (empty($this->getLines())) {
            return;
        }

        if (empty($inheritedRequiredProperties)) {
            return;
        }

        $this->addLine('');
        $this->addLine(sprintf('parent::%s(%s);', $this->name, implode(', ', $inheritedRequiredProperties)));
    }

    /**
     * {@inheritDoc}
     */
    public function configureBodyFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void
    {
        if ($modelPropertyBuilder->isInherited()) {
            return;
        }

        if ($modelPropertyBuilder->isRequired()) {
            $this->addLine(sprintf('$this->%1$s = $%1$s;', $modelPropertyBuilder->getName()));
            return;
        }

        $defaultValue = null;
        switch ($modelPropertyBuilder->getPhpType()) {
            case 'array':
                $defaultValue = '[]';
                break;
            case 'string':
                $defaultValue = '\'\'';
                break;
            case 'bool':
                $defaultValue = 'true';
                break;
            case 'int':
                $defaultValue = '0';
                break;
            case 'float':
                $defaultValue = '.0';
                break;
        }

        $modelPropertyBuilder->setValue($defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    public function configureParameterFromPropertyBuilder(ModelPropertyBuilderInterface $modelPropertyBuilder): void
    {
        if (false === $modelPropertyBuilder->isRequired()) {
            return;
        }

        $methodParameterBuilder = clone $this->methodParameterBuilderSkel;
        $methodParameterBuilder->configure(
            $modelPropertyBuilder->getTypes(),
            $modelPropertyBuilder->getName(),
            $modelPropertyBuilder->getValue(),
            false,
            $modelPropertyBuilder->getDescription()
        );

        $this->addParameter($methodParameterBuilder);
    }
}