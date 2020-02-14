<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodParameterViewInterface;

class MethodParameterGenerator extends AbstractGenerator implements MethodParameterGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesGenerator;

    /** @var string[] */
    protected $types = [];
    /** @var string */
    protected $name;
    /** @var string|null */
    protected $value;
    /** @var bool */
    protected $byReference = false;
    /** @var string */
    protected $description = '';

    /**
     * @param MethodParameterViewInterface $methodParameterView
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function __construct(
        MethodParameterViewInterface $methodParameterView,
        UsesGeneratorInterface $usesGenerator
    )
    {
        $this->setView($methodParameterView);
        $this->usesGenerator = $usesGenerator;
    }

    /**
     * @inheritDoc
     */
    public function configure(
        array $types,
        string $name,
        ?string $value = null,
        bool $byReference = false,
        string $description = ''
    ): void
    {
        $this->setTypes($types);
        $this->setName($name);
        $this->setValue($value);
        $this->setByReference($byReference);
        $this->setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpName(): string
    {
        return '$' . $this->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * {@inheritDoc}
     */
    public function setTypes(array $types): void
    {
        $this->types = [];
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addType(string $type): void
    {
        $type = $this->usesGenerator->guessUseOrReturnType($type);
        if (false === $this->hasType($type)) {
            $this->types[] = $type;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasType(string $type): bool
    {
        return false !== array_search($type, $this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpTypeFromTypes(): ?string
    {
        return self::getPhpType($this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return implode('|', $this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function isByReference(): bool
    {
        return $this->byReference;
    }

    /**
     * {@inheritDoc}
     */
    public function setByReference(bool $byReference): void
    {
        $this->byReference = $byReference;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
