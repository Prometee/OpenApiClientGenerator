<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

class GetterSetterGenerator implements GetterSetterGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesBuilder;
    /** @var MethodGeneratorInterface */
    protected $getterMethodBuilder;
    /** @var MethodGeneratorInterface */
    protected $setterMethodBuilder;

    /** @var PropertyGeneratorInterface */
    protected $propertyBuilder;
    /** @var bool */
    protected $readOnly = false;
    /** @var bool */
    protected $writeOnly = false;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     * @param MethodGeneratorInterface $getterMethodBuilder
     * @param MethodGeneratorInterface $setterMethodBuilder
     */
    public function __construct(
        UsesGeneratorInterface $usesBuilder,
        MethodGeneratorInterface $getterMethodBuilder,
        MethodGeneratorInterface $setterMethodBuilder
    )
    {
        $this->usesBuilder = $usesBuilder;
        $this->getterMethodBuilder = $getterMethodBuilder;
        $this->setterMethodBuilder = $setterMethodBuilder;
    }


    /**
     * {@inheritDoc}
     */
    public function configure(
        PropertyGeneratorInterface $propertyBuilder,
        bool $readOnly = false,
        bool $writeOnly = false
    ): void
    {
        $this->propertyBuilder = $propertyBuilder;
        $this->readOnly = $readOnly;
        $this->writeOnly = $writeOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethods(string $indent = null): array
    {
        $this->configureGetter($indent);
        $this->configureSetter($indent);

        return [
            $this->getterMethodBuilder,
            $this->setterMethodBuilder,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodName(?string $prefix = null, ?string $suffix = null): string
    {
        $name = trim($this->propertyBuilder->getName(), '_');
        $words = explode('_', $name);
        $words = array_map('ucfirst', $words);
        $name = '';
        foreach ($words as $word) {
            $name .= empty($word) ? '_' : $word;
        }

        return $prefix . $name . $suffix;
    }

    /**
     * {@inheritDoc}
     */
    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            $this->getterMethodBuilder->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::GETTER_PREFIX),
                $this->propertyBuilder->getTypes()
            );

            $this->getterMethodBuilder->addLine(
                sprintf('return $this->%s;', $this->propertyBuilder->getName())
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureSetter(string $indent = null): void
    {
        if (!$this->isReadOnly()) {
            $this->setterMethodBuilder->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::SETTER_PREFIX),
                ['void']
            );
            $methodParameterBuilder = clone $this->setterMethodBuilder->getMethodParameterBuilderSkel();
            $methodParameterBuilder->configure(
                $this->propertyBuilder->getTypes(),
                $this->propertyBuilder->getName()
            );

            $this->setterMethodBuilder->addParameter($methodParameterBuilder);

            $this->setterMethodBuilder->addLine(
                sprintf('$this->%s = %s;', $this->propertyBuilder->getName(), $methodParameterBuilder->getPhpName())
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function setReadOnly(bool $readOnly): void
    {
        $this->readOnly = $readOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function isWriteOnly(): bool
    {
        return $this->writeOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function setWriteOnly(bool $writeOnly): void
    {
        $this->writeOnly = $writeOnly;
    }
}
