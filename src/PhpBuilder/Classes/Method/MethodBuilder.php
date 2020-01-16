<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other\UsesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\PhpDoc\PhpDocBuilderInterface;

class MethodBuilder implements MethodBuilderInterface
{
    /** @var PhpDocBuilderInterface */
    protected $phpDocBuilder;
    /** @var UsesBuilderInterface */
    protected $usesBuilder;

    /** @var string */
    protected $scope = '';
    /** @var string */
    protected $name = '';
    /** @var string|null */
    protected $returnType;
    /** @var bool */
    protected $static = false;
    /** @var string */
    protected $description = '';
    /** @var MethodParameterBuilder[] */
    protected $parameters = [];
    /** @var string[] */
    protected $lines = [];
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
     * {@inheritDoc}
     */
    public function configure(
        string $scope,
        string $name,
        ?string $returnType = null,
        bool $static = false,
        string $description = ''
    )
    {
        $this->setScope($scope);
        $this->setName($name);
        $this->setReturnType($returnType);
        $this->setStatic($static);
        $this->setDescription($description);
        $this->setParameters([]);
        $this->setLines([]);

        $this->phpDocBuilder->configure();
        $this->hasAlreadyBeenGenerated = false;
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

            $additionalIndentation = count($this->parameters) > 4 ? "\n" . $indent . $indent : '';
            $parameters = [];
            foreach ($this->parameters as $methodParameterBuilder) {
                $parameters[] = $additionalIndentation . $methodParameterBuilder->build($indent);
            }
            $content .= implode(', ', $parameters);
            $content .= (count($this->parameters) > 4 ? "\n" . $indent : '') . ')';
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
            }
            if ($type !== 'null') {
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
        if (true === $this->usesBuilder->isAClass($returnType)) {
            $isArray = 1 === preg_match('#\[\]$#', $returnType);
            $class = trim($returnType, '\\[]');
            $this->usesBuilder->guessUse($class);
            $returnType = $this->usesBuilder->getInternalUseClassName($class) . ($isArray ? '[]' : '');
        }
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
}
