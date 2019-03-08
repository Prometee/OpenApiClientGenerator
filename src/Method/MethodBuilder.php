<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\PhpDocBuilder;
use Prometee\SwaggerClientBuilder\PhpDocBuilderInterface;

class MethodBuilder implements MethodBuilderInterface
{
    /** @var string */
    protected $scope;

    /** @var string */
    protected $name;

    /** @var null|string */
    protected $returnType;

    /** @var bool */
    protected $static;

    /** @var string */
    protected $description;

    /** @var MethodParameterBuilder[] */
    protected $parameters;

    /** @var array */
    protected $lines;

    /** @var PhpDocBuilderInterface */
    protected $phpDocBuilder;

    /** @var bool */
    protected $hasAlreadyBeenGenerated;

    /**
     * @param string $scope
     * @param string $name
     * @param string|null $returnType
     * @param bool $static
     * @param string $description
     */
    public function __construct(string $scope, string $name, ?string $returnType = null, bool $static = false, string $description = '')
    {
        $this->scope = $scope;
        $this->name = $name;
        $this->returnType = $returnType;
        $this->static = $static;
        $this->description = $description;
        $this->parameters = [];
        $this->lines = [];
        $this->resetPhpDocBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = '';
        if (count($this->lines) > 0) {
            $content .= "\n";

            $this->configurePhpDocBuilder();
            $content .= $this->phpDocBuilder->build($indent);

            $static = ($this->static) ? ' static ' : '';
            $content .= $indent . $this->scope . $static . ' function ' . $this->name . '(';

            $additionalIndentation = count($this->parameters) > 4 ? "\n".$indent.$indent : '';
            $parameters = [];
            foreach ($this->parameters as $methodParameterBuilder) {
                $parameters[] = $additionalIndentation.$methodParameterBuilder->build($indent);
            }
            $content .= implode(', ', $parameters);
            $content .= (count($this->parameters) > 4 ? "\n".$indent : '').')';
            if ($this->returnType !== null && $this->returnType !== 'mixed') {
                $content .= ': ' . $this->getPhpReturnType();
            }
            $content .= "\n";

            $content .= $indent . '{' . "\n";
            foreach ($this->lines as $line) {
                foreach (explode("\n", $line) as $innerLine) {
                    $content .= $indent . $indent . $innerLine . "\n";
                }
            }
            $content .= $indent . '}' . "\n";
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function configurePhpDocBuilder(): void
    {
        if (!$this->hasAlreadyBeenGenerated) {

            if (!empty($this->getDescription())) {
                $this->phpDocBuilder->addDescriptionLine($this->getDescription());
            }
            foreach ($this->parameters as $parameter) {
                $type = $this->phpDocBuilder::getPossibleTypesFromTypeName([$parameter->getType(), $parameter->getValueType()]);
                $this->phpDocBuilder->addParamLine($parameter->getPhpName(), $type, $parameter->getDescription());
            }
            if ($this->returnType !== null && $this->returnType !== 'void') {
                $type = $this->phpDocBuilder::getPossibleTypesFromTypeName([$this->returnType]);
                $this->phpDocBuilder->addReturnLine($type);
            }

            $this->hasAlreadyBeenGenerated = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnTypes(): ?array
    {
        if ($this->returnType !== null) {
            return explode('|', $this->returnType);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpReturnType(): ?string
    {
        if ($this->returnType === null) {
            return null;
        }

        $phpReturnType = '';
        if (in_array('null', $this->getReturnTypes())) {
            $phpReturnType = '?';
        }
        foreach ($this->getReturnTypes() as $type) {
            if (preg_match('#\[\]$#', $type)) {
                $phpReturnType .= 'array';
                break;
            } elseif ($type !== 'null') {
                $phpReturnType .= $type;
                break;
            }
        }

        return $phpReturnType;
    }

    /**
     * {@inheritdoc}
     */
    public function addParameter(MethodParameterBuilder $methodParameterBuilder): void
    {
        if (!$this->hasParameter($methodParameterBuilder)) {
            $this->setParameter($methodParameterBuilder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter(MethodParameterBuilder $methodParameterBuilder): bool
    {
        return isset($this->parameters[$methodParameterBuilder->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(MethodParameterBuilder $methodParameterBuilder): void
    {
        $this->parameters[$methodParameterBuilder->getName()] = $methodParameterBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function addLine(string $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * {@inheritdoc}
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * {@inheritdoc}
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function isStatic(): bool
    {
        return $this->static;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatic(bool $static): void
    {
        $this->static = $static;
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
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnType(?string $returnType): void
    {
        $this->returnType = $returnType;
    }

    /**
     * {@inheritdoc}
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * {@inheritdoc}
     */
    public function setLines(array $lines): void
    {
        $this->lines = $lines;
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

    /**
     * {@inheritdoc}
     */
    public function resetPhpDocBuilder(): void
    {
        $this->phpDocBuilder = new PhpDocBuilder();
        $this->hasAlreadyBeenGenerated = false;
    }
}
