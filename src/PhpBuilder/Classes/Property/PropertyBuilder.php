<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Property;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilderInterface;

class PropertyBuilder implements PropertyBuilderInterface
{
    /** @var UsesBuilderInterface  */
    private $usesBuilder;
    /** @var PhpDocBuilderInterface */
    protected $phpDocBuilder;

    /** @var string */
    protected $scope = 'private';
    /** @var string */
    protected $name;
    /** @var string|null */
    protected $type;
    /** @var string|null */
    protected $value;
    /** @var string */
    protected $description = '';
    /** @var bool */
    protected $hasAlreadyBeenGenerated = false;

    /**
     * @param UsesBuilderInterface $usesBuilder
     * @param PhpDocBuilderInterface $phpDocBuilder
     */
    public function __construct(
        UsesBuilderInterface $usesBuilder,
        PhpDocBuilderInterface $phpDocBuilder
    )
    {
        $this->usesBuilder = $usesBuilder;
        $this->phpDocBuilder = $phpDocBuilder;
    }

    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string $description
     */
    public function configure(
        string $name,
        ?string $type = null,
        ?string $value = null,
        string $description = ''
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;

        $this->phpDocBuilder->configure();
        $this->hasAlreadyBeenGenerated = false;
    }

    /**
     * {@inheritdoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = "\n";

        $this->configurePhpDocBuilder();
        $content .= $this->phpDocBuilder->build($indent);

        $content .= $indent . $this->scope . ' ';
        $content .= $this->getPhpName();
        $content .= ($this->value !== null) ? '=' . $this->value : '';
        $content .= ';';

        $content .= "\n";

        return $content;
    }

    public function configurePhpDocBuilder(): void
    {
        if (!$this->hasAlreadyBeenGenerated) {
            if (!empty($this->description)) {
                $this->phpDocBuilder->addDescriptionLine($this->description);
            }
            $this->phpDocBuilder->addVarLine($this->type);

            $this->hasAlreadyBeenGenerated = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpName(): string
    {
        return '$' . $this->name;
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
            }
            if ($type !== 'null') {
                $phpType .= $type;

                break;
            }
        }

        return $phpType;
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function getPhpDocBuilder(): PhpDocBuilderInterface
    {
        return $this->phpDocBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhpDocBuilder(PhpDocBuilderInterface $phpDocBuilder): void
    {
        $this->phpDocBuilder = $phpDocBuilder;
    }
}
