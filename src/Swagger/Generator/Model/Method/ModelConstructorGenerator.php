<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Method\ConstructorGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute\ModelPropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Other\ModelPropertiesGeneratorInterface;

class ModelConstructorGenerator extends ConstructorGenerator implements ModelConstructorGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureFromPropertiesGenerator(ModelPropertiesGeneratorInterface $modelPropertiesGenerator): void
    {
        $inheritedRequiredProperties = [];
        /** @var ModelPropertyGeneratorInterface $modelPropertyGenerator */
        foreach ($modelPropertiesGenerator->getProperties() as $modelPropertyGenerator) {
            $this->configureParameterFromPropertyGenerator($modelPropertyGenerator);
            $this->configureBodyFromPropertyGenerator($modelPropertyGenerator);
            if (false === $modelPropertyGenerator->isInherited()) {
                continue;
            }
            if (false === $modelPropertyGenerator->isRequired()) {
                continue;
            }
            $inheritedRequiredProperties[] = $modelPropertyGenerator->getPhpName();
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
    public function configureBodyFromPropertyGenerator(ModelPropertyGeneratorInterface $modelPropertyGenerator): void
    {
        if ($modelPropertyGenerator->isInherited()) {
            return;
        }

        if ($modelPropertyGenerator->isRequired()) {
            $this->addLine(sprintf('$this->%1$s = $%1$s;', $modelPropertyGenerator->getName()));
            return;
        }

        $defaultValue = $modelPropertyGenerator->getDefaultValueFromTypes();
        $modelPropertyGenerator->setValue($defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    public function configureParameterFromPropertyGenerator(ModelPropertyGeneratorInterface $modelPropertyGenerator): void
    {
        if (false === $modelPropertyGenerator->isRequired()) {
            return;
        }

        $methodParameterGenerator = clone $this->methodParameterGeneratorSkel;
        $methodParameterGenerator->configure(
            $modelPropertyGenerator->getTypes(),
            $modelPropertyGenerator->getName(),
            $modelPropertyGenerator->getValue(),
            false,
            $modelPropertyGenerator->getDescription()
        );

        $this->addParameter($methodParameterGenerator);
    }
}