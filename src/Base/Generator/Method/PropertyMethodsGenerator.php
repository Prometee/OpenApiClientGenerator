<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Factory\MethodGeneratorFactoryInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

class PropertyMethodsGenerator implements PropertyMethodsGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesGenerator;

    /** @var PropertyGeneratorInterface */
    protected $propertyGenerator;
    /** @var bool */
    protected $readOnly = false;
    /** @var bool */
    protected $writeOnly = false;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function __construct(
        UsesGeneratorInterface $usesGenerator
    )
    {
        $this->usesGenerator = $usesGenerator;
    }

    /**
     * @inheritDoc
     */
    public function configure(
        PropertyGeneratorInterface $propertyGenerator,
        bool $readOnly = false,
        bool $writeOnly = false
    ): void
    {
        $this->propertyGenerator = $propertyGenerator;
        $this->readOnly = $readOnly;
        $this->writeOnly = $writeOnly;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethods(MethodGeneratorFactoryInterface $methodFactory, string $indent = null): array
    {
        $propertyMethodsGenerator = null;
        if (null !== $this->propertyGenerator->getTypes()) {
            foreach ($this->propertyGenerator->getTypes() as $type) {
                if ('bool' === $type) {
                    $propertyMethodsGenerator = $methodFactory->createIsserSetterGenerator($this->usesGenerator);
                    break;
                }
                if (preg_match('#\[\]$#', $type)) {
                    $propertyMethodsGenerator = $methodFactory->createArrayGetterSetterGenerator($this->usesGenerator);
                    break;
                }
            }
        }

        if (null === $propertyMethodsGenerator) {
            $propertyMethodsGenerator = $methodFactory->createGetterSetterGenerator($this->usesGenerator);
        }

        $propertyMethodsGenerator->configure(
            $this->propertyGenerator,
            $this->readOnly,
            $this->writeOnly
        );

        return $propertyMethodsGenerator->getMethods($indent);
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
