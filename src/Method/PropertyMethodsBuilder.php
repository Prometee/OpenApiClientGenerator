<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\Property\PropertyBuilderInterface;

class PropertyMethodsBuilder
{
    /** @var PropertyBuilderInterface */
    protected $propertyBuilder;

    /** @var bool */
    protected $readOnly;

    /** @var bool */
    protected $writeOnly;

    /**
     * @param PropertyBuilderInterface $propertyBuilder
     * @param bool $readOnly
     * @param bool $writeOnly
     */
    public function __construct(PropertyBuilderInterface $propertyBuilder, bool $readOnly = false, bool $writeOnly = false)
    {
        $this->propertyBuilder = $propertyBuilder;
        $this->readOnly = $readOnly;
        $this->writeOnly = $writeOnly;
    }

    /**
     * @param string|null $indent
     *
     * @return MethodBuilderInterface[]
     */
    public function getMethods(string $indent = null): array
    {
        $propertyMethodsBuilder = null;
        foreach ($this->propertyBuilder->getTypes() as $type) {
            if ($type === 'bool') {
                $propertyMethodsBuilder = new IsserSetterBuilder($this->propertyBuilder, $this->readOnly, $this->writeOnly);

                break;
            }
            if (preg_match('#\[\]$#', $type)) {
                $propertyMethodsBuilder = new ArrayGetterSetterBuilder($this->propertyBuilder, $this->readOnly, $this->writeOnly);

                break;
            }
        }

        if ($propertyMethodsBuilder === null) {
            $propertyMethodsBuilder = new GetterSetterBuilder($this->propertyBuilder, $this->readOnly, $this->writeOnly);
        }

        return $propertyMethodsBuilder->getMethods($indent);
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     */
    public function setReadOnly(bool $readOnly): void
    {
        $this->readOnly = $readOnly;
    }

    /**
     * @return bool
     */
    public function isWriteOnly(): bool
    {
        return $this->writeOnly;
    }

    /**
     * @param bool $writeOnly
     */
    public function setWriteOnly(bool $writeOnly): void
    {
        $this->writeOnly = $writeOnly;
    }
}
