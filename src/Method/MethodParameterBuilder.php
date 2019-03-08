<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\BuilderInterface;

class MethodParameterBuilder implements BuilderInterface
{
    /** @var string|null */
    protected $type;

    /** @var string */
    protected $name;

    /** @var string|null */
    protected $value;

    /** @var bool */
    protected $byReference;

    /** @var string */
    protected $description;

    /**
     * @param string|null $type
     * @param string $name
     * @param string|null $value
     * @param bool $byReference
     * @param string|null $description
     */
    public function __construct(?string $type, string $name, ?string $value = null, bool $byReference = false, string $description = '')
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->byReference = $byReference;
        $this->description = $description;
    }

    public function build(string $indent = null): ?string
    {
        $content = '';

        $content .= ($this->type !== null) ? $this->getPhpType().' ' : '';
        $content .= $this->byReference ? '&' : '';
        $content .= $this->getPhpName();
        $content .= ($this->value !== null) ? ' = '.$this->value : '';

        return $content;
    }

    /**
     * @return string
     */
    public function getPhpName(): string
    {
        return '$'.$this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes(): ?array
    {
        if ($this->type !== null) {
            return explode('|', $this->type);
        }

        return null;
    }

    /**
     * {@inheritdoc}
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
            } elseif ($type !== 'null') {
                $phpType .= $type;
                break;
            }
        }

        return $phpType;
    }

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
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isByReference(): bool
    {
        return $this->byReference;
    }

    /**
     * @param bool $byReference
     */
    public function setByReference(bool $byReference): void
    {
        $this->byReference = $byReference;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
