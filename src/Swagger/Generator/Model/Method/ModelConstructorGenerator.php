<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Method\ConstructorGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;

class ModelConstructorGenerator extends ConstructorGenerator implements ModelConstructorGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureFromPropertiesBuilder(ModelPropertiesGeneratorInterface $modelPropertiesBuilder): void
    {
        $inheritedRequiredProperties = [];
        /** @var ModelPropertyGeneratorInterface $modelPropertyBuilder */
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
    public function configureBodyFromPropertyBuilder(ModelPropertyGeneratorInterface $modelPropertyBuilder): void
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
    public function configureParameterFromPropertyBuilder(ModelPropertyGeneratorInterface $modelPropertyBuilder): void
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