<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

class MethodParameterGenerator implements MethodParameterGeneratorInterface
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
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function __construct(UsesGeneratorInterface $usesGenerator)
    {
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
    public function generate(string $indent = null): ?string
    {
        $content = '';

        $content .= !empty($this->types) ? $this->getPhpType() . ' ' : '';
        $content .= $this->byReference ? '&' : '';
        $content .= $this->getPhpName();
        $content .= ($this->value !== null) ? ' = ' . $this->value : '';

        return $content;
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
    public function getPhpType(): ?string
    {
        if (empty($this->types)) {
            return null;
        }

        $phpType = '';
        if (in_array('null', $this->types)) {
            $phpType = '?';
        }
        foreach ($this->getTypes() as $type) {
            if (preg_match('#\[\]$#', $type)) {
                $phpType .= 'array';

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
    public function getValueType(): ?string
    {
        if (null === $this->value) {
            return null;
        }

        if ($this->value === '[]') {
            return 'array';
        }

        if (preg_match('#^[\'"].*[\'"]$#', $this->value)) {
            return 'string';
        }

        if (in_array($this->value, ['true', 'false'])) {
            return 'bool';
        }

        if (preg_match('#^[0-9]+$#', $this->value)) {
            return 'int';
        }

        if (preg_match('#^[0-9\.]+$#', $this->value)) {
            return 'float';
        }

        return null;
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
