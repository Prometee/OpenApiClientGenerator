<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Attribute;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\Attribute\PropertyViewInterface;

class PropertyGenerator extends AbstractGenerator implements PropertyGeneratorInterface
{
    /** @var UsesGeneratorInterface  */
    protected $usesGenerator;
    /** @var PhpDocGeneratorInterface */
    protected $phpDocGenerator;

    /** @var string */
    protected $scope = 'private';
    /** @var string */
    protected $name;
    /** @var string|null */
    protected $value;
    /** @var string */
    protected $description = '';
    /** @var string[] */
    protected $types;

    /**
     * @param PropertyViewInterface $propertyView
     * @param UsesGeneratorInterface $usesGenerator
     * @param PhpDocGeneratorInterface $phpDocGenerator
     */
    public function __construct(
        PropertyViewInterface $propertyView,
        UsesGeneratorInterface $usesGenerator,
        PhpDocGeneratorInterface $phpDocGenerator
    )
    {
        $this->setView($propertyView);
        $this->usesGenerator = $usesGenerator;
        $this->phpDocGenerator = $phpDocGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(
        string $name,
        array $types = [],
        ?string $value = null,
        string $description = ''
    )
    {
        $this->setName($name);
        $this->setTypes($types);
        $this->setValue($value);
        $this->setDescription($description);

        $this->phpDocGenerator->configure();
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null, string $eol = null): ?string
    {
        $this->configurePhpDocGenerator();
        return parent::generate($indent, $eol);
    }

    public function configurePhpDocGenerator(): void
    {
        if (!empty($this->description)) {
            $this->phpDocGenerator->addDescriptionLine($this->description);
        }
        $this->phpDocGenerator->addVarLine($this->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpName(): string
    {
        return sprintf('$%s', $this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        if (empty($this->types)) {
            return null;
        }
        return implode('|', $this->types);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpTypeFromTypes(): ?string
    {
        return self::getPhpType($this->types);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValueFromTypes(): ?string
    {
        return self::getPhpDefaultValue($this->types);
    }

    /**
     * {@inheritdoc}
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     *{@inheritdoc}
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpDocGenerator(): PhpDocGeneratorInterface
    {
        return $this->phpDocGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhpDocGenerator(PhpDocGeneratorInterface $phpDocGenerator): void
    {
        $this->phpDocGenerator = $phpDocGenerator;
    }
}
