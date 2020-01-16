<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Factory\MethodFactoryInterface;

class ArrayGetterSetterBuilder extends GetterSetterBuilder implements ArrayGetterSetterBuilderInterface
{
    /** @var MethodBuilderInterface */
    protected $hasGetterMethod;
    /** @var MethodBuilderInterface */
    protected $addSetterMethod;
    /** @var MethodBuilderInterface */
    protected $removeSetterMethod;

    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param MethodFactoryInterface $methodFactory
     */
    public function __construct(
        UsesBuilderInterface $usesBuilder,
        MethodFactoryInterface $methodFactory
    )
    {
        parent::__construct($usesBuilder, $methodFactory);

        $this->hasGetterMethod = $this->methodFactory->createMethodBuilder($this->usesBuilder);
        $this->addSetterMethod = $this->methodFactory->createMethodBuilder($this->usesBuilder);
        $this->removeSetterMethod = $this->methodFactory->createMethodBuilder($this->usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            parent::configureGetter($indent);
            $this->configureHasGetter($indent);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureSetter(string $indent = null): void
    {
        if (!$this->isReadOnly()) {
            $this->configureAddSetter($indent);
            $this->configureRemoveSetter($indent);

            parent::configureSetter($indent);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureHasGetter(string $indent = null): void
    {
        $this->hasGetterMethod->configure(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
            'bool'
        );

        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $this->usesBuilder
        );
        $methodParameterBuilder->configure(
            (array) $this->getSingleTypeName(),
            $this->getSingleName()
        );
        $this->hasGetterMethod->addParameter($methodParameterBuilder);

        $methodParameterBuilder2 = $this->methodFactory->createMethodParameterBuilder(
            $this->usesBuilder
        );
        $methodParameterBuilder2->configure(
            (array) 'bool',
            'strict',
            'true'
        );
        $this->hasGetterMethod->addParameter($methodParameterBuilder2);

        $format = '';
        if (preg_match('#^\?#', $methodParameterBuilder->getPhpType())) {
            $format .= 'if (null === $this->%2$s) {' . "\n";
            $format .= '%4$sreturn false;' . "\n";
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

    /**
     * {@inheritDoc}
     */
    public function configureAddSetter(string $indent = null): void
    {
        $this->addSetterMethod->configure(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::ADD_SETTER_PREFIX),
            'void'
        );

        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $this->usesBuilder
        );
        $methodParameterBuilder->configure(
            (array) $this->getSingleTypeName(),
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
        $format .= '$this->%4$s[] = %2$s;';

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

    /**
     * {@inheritDoc}
     */
    public function configureRemoveSetter(string $indent = null): void
    {
        $this->removeSetterMethod->configure(
            MethodBuilderInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::REMOVE_SETTER_PREFIX),
            'void'
        );
        $methodParameterBuilder = $this->methodFactory->createMethodParameterBuilder(
            $this->usesBuilder
        );
        $methodParameterBuilder->configure(
            (array) $this->getSingleTypeName(),
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
     * {@inheritDoc}
     */
    public function getSingleMethodName(?string $prefix = null, ?string $suffix = null): string
    {
        return $this->getMethodName($prefix, $suffix);
    }

    /**
     * {@inheritDoc}
     */
    public function getSingleTypeName(): ?string
    {
        if (empty($this->propertyBuilder->getTypes())) {
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
     * {@inheritDoc}
     */
    public function getSingleName(): string
    {
        return $this->propertyBuilder->getName();
    }
}
