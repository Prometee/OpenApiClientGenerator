<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

class PropertyMethodsBuilder implements PropertyMethodsBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;

    /** @var PropertyBuilderInterface */
    protected $propertyBuilder;
    /** @var bool */
    protected $readOnly = false;
    /** @var bool */
    protected $writeOnly = false;

    /**
     * @param UsesBuilderInterface $usesBuilder
     */
    public function __construct(
        UsesBuilderInterface $usesBuilder
    )
    {
        $this->usesBuilder = $usesBuilder;
    }

    /**
     * @inheritDoc
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
    public function getMethods(MethodFactoryInterface $methodFactory, string $indent = null): array
    {
        $propertyMethodsBuilder = null;
        if (null !== $this->propertyBuilder->getTypes()) {
            foreach ($this->propertyBuilder->getTypes() as $type) {
                if ('bool' === $type) {
                    $propertyMethodsBuilder = $methodFactory->createIsserSetterBuilderBuilder($this->usesBuilder);
                    break;
                }
                if (preg_match('#\[\]$#', $type)) {
                    $propertyMethodsBuilder = $methodFactory->createArrayGetterSetterBuilder($this->usesBuilder);
                    break;
                }
            }
        }

        if (null === $propertyMethodsBuilder) {
            $propertyMethodsBuilder = $methodFactory->createGetterSetterBuilder($this->usesBuilder);
        }

        $propertyMethodsBuilder->configure(
            $this->propertyBuilder,
            $this->readOnly,
            $this->writeOnly
        );

        return $propertyMethodsBuilder->getMethods($indent);
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
