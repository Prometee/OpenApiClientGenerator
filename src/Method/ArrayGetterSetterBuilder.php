<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

class ArrayGetterSetterBuilder extends GetterSetterBuilder
{
    const HAS_GETTER_PREFIX = 'has';
    const ADD_SETTER_PREFIX = 'add';
    const REMOVE_SETTER_PREFIX = 'remove';

    protected $hasGetterMethod;
    protected $addSetterMethod;
    protected $removeSetterMethod;

    public function getMethods(string $indent = null): array
    {
        return array_merge(
            parent::getMethods($indent),
            [
                $this->hasGetterMethod,
                $this->addSetterMethod,
                $this->removeSetterMethod,
            ]
        );
    }

    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            parent::configureGetter($indent);
            $this->configureHasGetter($indent);
        }
    }

    public function configureSetter(string $indent = null): void
    {
        if (!$this->isReadOnly()) {
            $this->configureAddSetter($indent);
            $this->configureRemoveSetter($indent);

            parent::configureSetter($indent);
        }
    }

    public function configureHasGetter(string $indent = null): void
    {
        $this->hasGetterMethod = new MethodBuilder(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
            'bool'
        );

        $methodParameterBuilder = new MethodParameterBuilder(
            $this->getSingleTypeName(),
            $this->getSingleName()
        );
        $this->hasGetterMethod->addParameter($methodParameterBuilder);

        $methodParameterBuilder2 = new MethodParameterBuilder(
            'bool',
            'strict',
            'true'
        );
        $this->hasGetterMethod->addParameter($methodParameterBuilder2);

        $format = '';
        if (preg_match('#^\?#', $methodParameterBuilder->getPhpType())) {
            $format .= 'if (null === $this->%2$s) {' . "\n";
            $format .= '%5$sreturn false;' . "\n";
            $format .= '}' . "\n\n";
        }
        $format .= 'return in_array($%1$s, $this->%2$s, %3$s);';

        $this->hasGetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleName(),
                $this->propertyBuilder->getName(),
                $methodParameterBuilder2->getPhpName(),
                $indent
            )
        );
    }

    public function configureAddSetter(string $indent = null): void
    {
        $this->addSetterMethod = new MethodBuilder(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::ADD_SETTER_PREFIX),
            'void'
        );

        $methodParameterBuilder = new MethodParameterBuilder(
            $this->getSingleTypeName(),
            $this->getSingleName()
        );
        $this->addSetterMethod->addParameter($methodParameterBuilder);

        $format  = 'if ($this->%1$s(%2$s)) {' . "\n";
        $format .= '%3$sreturn;' . "\n";
        $format .= '}' . "\n\n";
        if (preg_match('#^\?#', $methodParameterBuilder->getPhpType())) {
            $format .= 'if (null === $this->%4$s) {' . "\n";
            $format .= '%3$s$this->%4$s = [];' . "\n";
            $format .= '}' . "\n\n";
        }
        $format .= '%3$s$this->%4$s[] = %2$s;';

        $this->addSetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
                $methodParameterBuilder->getPhpName(),
                $indent,
                $this->propertyBuilder->getName()
            )
        );
    }

    public function configureRemoveSetter(string $indent = null): void
    {
        $this->removeSetterMethod = new MethodBuilder(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::REMOVE_SETTER_PREFIX),
            'void'
        );
        $methodParameterBuilder = new MethodParameterBuilder(
            $this->getSingleTypeName(),
            $this->getSingleName()
        );

        $this->removeSetterMethod->addParameter($methodParameterBuilder);

        $format = 'if ($this->%1$s(%2$s)) {' . "\n";
        $format .= '%3$s$index = array_search(%2$s, $this->%4$s);' . "\n";
        $format .= '%3$sunset($this->%4$s[$index]);' . "\n";
        $format .= '}';

        $this->removeSetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
                $methodParameterBuilder->getPhpName(),
                $indent,
                $this->propertyBuilder->getName()
            )
        );
    }

    /**
     * @param string|null $prefix
     * @param string|null $suffix
     *
     * @return string
     */
    public function getSingleMethodName(?string $prefix = null, ?string $suffix = null): string
    {
        return $this->getMethodName($prefix, $suffix);
    }

    /**
     * @return string|null
     */
    public function getSingleTypeName(): ?string
    {
        if ($this->propertyBuilder->getType() === null) {
            return null;
        }

        $phpType = '';
        if (in_array('null', $this->propertyBuilder->getTypes())) {
            $phpType = '?';
        }
        foreach ($this->propertyBuilder->getTypes() as $type) {
            if (preg_match('#\[\]$#', $type)) {
                $phpType .= rtrim($type, '[]');

                break;
            }
            if ($type !== 'null') {
                $phpType .= $type;

                break;
            }
        }

        return $phpType;
    }

    /**
     * @return string|string[]|null
     */
    public function getSingleName()
    {
        return $this->propertyBuilder->getName();
    }
}
