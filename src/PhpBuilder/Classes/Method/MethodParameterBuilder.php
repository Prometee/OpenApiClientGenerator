<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;

class MethodParameterBuilder implements MethodParameterBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;

    /** @var string|null */
    protected $type;
    /** @var string */
    protected $name;
    /** @var string|null */
    protected $value;
    /** @var bool */
    protected $byReference = false;
    /** @var string */
    protected $description = '';

    /**
     * @param UsesBuilderInterface $usesBuilder
     */
    public function __construct(UsesBuilderInterface $usesBuilder)
    {
        $this->usesBuilder = $usesBuilder;
    }

    /**
     * @inheritDoc
     */
    public function configure(
        ?string $type,
        string $name,
        ?string $value = null,
        bool $byReference = false,
        string $description = ''
    ):void
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->byReference = $byReference;
        $this->description = $description;
    }

    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = '';

        $content .= ($this->type !== null) ? $this->getPhpType() . ' ' : '';
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
    public function getTypes(): ?array
    {
        if ($this->type !== null) {
            return explode('|', $this->type);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPhpType(): ?string
    {
        if ($this->type === null) {
            return null;
        }

        $phpType = '';
        if (in_array('null', $this->getTypes())) {
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
    public function getValueType()
    {
        if ($this->value === null) {
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

        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
