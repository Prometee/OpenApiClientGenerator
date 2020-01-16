<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property\PropertyBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;

class GetterSetterBuilder implements GetterSetterBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;
    /** @var MethodFactoryInterface */
    protected $methodFactory;
    /** @var MethodBuilderInterface */
    protected $getterMethodBuilder;
    /** @var MethodBuilderInterface */
    protected $setterMethodBuilder;

    /** @var PropertyBuilderInterface */
    protected $propertyBuilder;
    /** @var bool */
    protected $readOnly = false;
    /** @var bool */
    protected $writeOnly = false;

    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param MethodFactoryInterface $methodFactory
     */
    public function __construct(
        UsesBuilderInterface $usesBuilder,
        MethodFactoryInterface $methodFactory
    )
    {
        $this->usesBuilder = $usesBuilder;
        $this->methodFactory = $methodFactory;

        $this->getterMethodBuilder = $this->methodFactory->createMethodBuilder($this->usesBuilder);
        $this->setterMethodBuilder = $this->methodFactory->createMethodBuilder($this->usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(
        PropertyBuilderInterface $propertyBuilder,
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
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::GETTER_PREFIX),
                $this->propertyBuilder->getType()
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
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::SETTER_PREFIX),
                'void'
            );
            $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
                $this->usesBuilder
            );
            $methodParameterBuilder->configure(
                (array) $this->propertyBuilder->getType(),
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
