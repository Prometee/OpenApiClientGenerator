<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

class ArrayGetterSetterGenerator extends GetterSetterGenerator implements ArrayGetterSetterGeneratorInterface
{
    /** @var MethodGeneratorInterface */
    protected $hasGetterMethod;
    /** @var MethodGeneratorInterface */
    protected $addSetterMethod;
    /** @var MethodGeneratorInterface */
    protected $removeSetterMethod;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param MethodGeneratorInterface $getterMethodGenerator
     * @param MethodGeneratorInterface $setterMethodGenerator
     * @param MethodGeneratorInterface $hasGetterMethod
     * @param MethodGeneratorInterface $addSetterMethod
     * @param MethodGeneratorInterface $removeSetterMethod
     */
    public function __construct(
        UsesGeneratorInterface $usesGenerator,
        MethodGeneratorInterface $getterMethodGenerator,
        MethodGeneratorInterface $setterMethodGenerator,
        MethodGeneratorInterface $hasGetterMethod,
        MethodGeneratorInterface $addSetterMethod,
        MethodGeneratorInterface $removeSetterMethod
    )
    {
        parent::__construct($usesGenerator, $getterMethodGenerator, $setterMethodGenerator);

        $this->hasGetterMethod = $hasGetterMethod;
        $this->addSetterMethod = $addSetterMethod;
        $this->removeSetterMethod = $removeSetterMethod;
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
            MethodGeneratorInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
            ['bool']
        );

        $methodParameterGenerator = clone $this->hasGetterMethod->getMethodParameterGeneratorSkel();
        $methodParameterGenerator->configure(
            (array) $this->getSingleTypeName(),
            $this->getSingleName()
        );
        $this->hasGetterMethod->addParameter($methodParameterGenerator);

        $methodParameterGenerator2 = clone $this->hasGetterMethod->getMethodParameterGeneratorSkel();
        $methodParameterGenerator2->configure(
            (array) 'bool',
            'strict',
            'true'
        );
        $this->hasGetterMethod->addParameter($methodParameterGenerator2);

        $format = '';
        if (preg_match('#^\?#', $methodParameterGenerator->getPhpTypeFromTypes())) {
            $format .= 'if (null === $this->%2$s) {' . "\n";
            $format .= '%4$sreturn false;' . "\n";
            $format .= '}' . "\n\n";
        }
        $format .= 'return in_array($%1$s, $this->%2$s, %3$s);';

        $this->hasGetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleName(),
                $this->propertyGenerator->getName(),
                $methodParameterGenerator2->getPhpName(),
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
            MethodGeneratorInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::ADD_SETTER_PREFIX),
            ['void']
        );

        $methodParameterGenerator = clone $this->addSetterMethod->getMethodParameterGeneratorSkel();
        $methodParameterGenerator->configure(
            (array) $this->getSingleTypeName(),
            $this->getSingleName()
        );
        $this->addSetterMethod->addParameter($methodParameterGenerator);

        $format  = 'if ($this->%1$s(%2$s)) {' . "\n";
        $format .= '%3$sreturn;' . "\n";
        $format .= '}' . "\n\n";
        if (preg_match('#^\?#', $methodParameterGenerator->getPhpTypeFromTypes())) {
            $format .= 'if (null === $this->%4$s) {' . "\n";
            $format .= '%3$s$this->%4$s = [];' . "\n";
            $format .= '}' . "\n\n";
        }
        $format .= '$this->%4$s[] = %2$s;';

        $this->addSetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
                $methodParameterGenerator->getPhpName(),
                $indent,
                $this->propertyGenerator->getName()
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureRemoveSetter(string $indent = null): void
    {
        $this->removeSetterMethod->configure(
            MethodGeneratorInterface::SCOPE_PUBLIC,
            $this->getSingleMethodName(static::REMOVE_SETTER_PREFIX),
            ['void']
        );
        $methodParameterGenerator = clone $this->removeSetterMethod->getMethodParameterGeneratorSkel();
        $methodParameterGenerator->configure(
            (array) $this->getSingleTypeName(),
            $this->getSingleName()
        );

        $this->removeSetterMethod->addParameter($methodParameterGenerator);

        $format = 'if ($this->%1$s(%2$s)) {' . "\n";
        $format .= '%3$s$index = array_search(%2$s, $this->%4$s);' . "\n";
        $format .= '%3$sunset($this->%4$s[$index]);' . "\n";
        $format .= '}';

        $this->removeSetterMethod->addLine(
            sprintf(
                $format,
                $this->getSingleMethodName(static::HAS_GETTER_PREFIX),
                $methodParameterGenerator->getPhpName(),
                $indent,
                $this->propertyGenerator->getName()
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
        if (empty($this->propertyGenerator->getTypes())) {
            return null;
        }

        $phpType = '';
        if (in_array('null', $this->propertyGenerator->getTypes())) {
            $phpType = '?';
        }
        foreach ($this->propertyGenerator->getTypes() as $type) {
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
        return $this->propertyGenerator->getName();
    }
}
