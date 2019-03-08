<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\Property\PropertyBuilderInterface;

class GetterSetterBuilder
{
    public const GETTER_PREFIX = 'get';
    public const SETTER_PREFIX = 'set';

    /** @var MethodBuilderInterface */
    protected $getterMethod;

    /** @var MethodBuilderInterface */
    protected $setterMethod;

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
     * @return MethodBuilderInterface[]
     */
    public function getMethods(string $indent = null): array
    {
        $this->configureGetter($indent);
        $this->configureSetter($indent);

        return [
            $this->getterMethod,
            $this->setterMethod,
        ];
    }

    /**
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string
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
        return $prefix.$name.$suffix;
    }

    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            $this->getterMethod = new MethodBuilder(
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::GETTER_PREFIX),
                $this->propertyBuilder->getType()
            );

            $this->getterMethod->addLine(
                sprintf('return $this->%s;', $this->propertyBuilder->getName())
            );
        }
    }

    public function configureSetter(string $indent = null): void
    {
        if (!$this->isReadOnly()) {
            $this->setterMethod = new MethodBuilder(
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::SETTER_PREFIX),
                'void'
            );
            $methodParameterBuilder = new MethodParameterBuilder(
                $this->propertyBuilder->getType(),
                $this->propertyBuilder->getName()
            );

            $this->setterMethod->addParameter($methodParameterBuilder);

            $this->setterMethod->addLine(
                sprintf('$this->%s = %s;', $this->propertyBuilder->getName(), $methodParameterBuilder->getPhpName())
            );
        }
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
