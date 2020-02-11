<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

class GetterSetterGenerator implements GetterSetterGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesGenerator;
    /** @var MethodGeneratorInterface */
    protected $getterMethodGenerator;
    /** @var MethodGeneratorInterface */
    protected $setterMethodGenerator;

    /** @var PropertyGeneratorInterface */
    protected $propertyGenerator;
    /** @var bool */
    protected $readOnly = false;
    /** @var bool */
    protected $writeOnly = false;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param MethodGeneratorInterface $getterMethodGenerator
     * @param MethodGeneratorInterface $setterMethodGenerator
     */
    public function __construct(
        UsesGeneratorInterface $usesGenerator,
        MethodGeneratorInterface $getterMethodGenerator,
        MethodGeneratorInterface $setterMethodGenerator
    )
    {
        $this->usesGenerator = $usesGenerator;
        $this->getterMethodGenerator = $getterMethodGenerator;
        $this->setterMethodGenerator = $setterMethodGenerator;
    }


    /**
     * {@inheritDoc}
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
    public function getMethods(string $indent = null): array
    {
        $this->configureGetter($indent);
        $this->configureSetter($indent);

        return [
            $this->getterMethodGenerator,
            $this->setterMethodGenerator,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodName(?string $prefix = null, ?string $suffix = null): string
    {
        $name = trim($this->propertyGenerator->getName(), '_');
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
            $this->getterMethodGenerator->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::GETTER_PREFIX),
                $this->propertyGenerator->getTypes()
            );

            $this->getterMethodGenerator->addLine(
                sprintf('return $this->%s;', $this->propertyGenerator->getName())
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureSetter(string $indent = null): void
    {
        if (!$this->isReadOnly()) {
            $this->setterMethodGenerator->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::SETTER_PREFIX),
                ['void']
            );
            $methodParameterGenerator = clone $this->setterMethodGenerator->getMethodParameterGeneratorSkel();
            $methodParameterGenerator->configure(
                $this->propertyGenerator->getTypes(),
                $this->propertyGenerator->getName()
            );

            $this->setterMethodGenerator->addParameter($methodParameterGenerator);

            $this->setterMethodGenerator->addLine(
                sprintf('$this->%s = %s;', $this->propertyGenerator->getName(), $methodParameterGenerator->getPhpName())
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
