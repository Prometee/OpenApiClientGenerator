<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;

class MethodGenerator implements MethodGeneratorInterface
{
    /** @var PhpDocGeneratorInterface */
    protected $phpDocBuilder;
    /** @var UsesGeneratorInterface */
    protected $usesBuilder;
    /** @var MethodParameterGeneratorInterface */
    protected $methodParameterBuilderSkel;

    /** @var string */
    protected $scope = '';
    /** @var string */
    protected $name = '';
    /** @var string[] */
    protected $returnTypes = [];
    /** @var bool */
    protected $static = false;
    /** @var string */
    protected $description = '';
    /** @var MethodParameterGeneratorInterface[] */
    protected $parameters = [];
    /** @var string[] */
    protected $lines = [];
    /** @var bool */
    protected $hasAlreadyBeenGenerated = false;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     * @param PhpDocGeneratorInterface $phpDocBuilder
     * @param MethodParameterGeneratorInterface $methodParameterBuilderSkel
     */
    public function __construct(
        UsesGeneratorInterface $usesBuilder,
        PhpDocGeneratorInterface $phpDocBuilder,
        MethodParameterGeneratorInterface $methodParameterBuilderSkel
    )
    {
        $this->usesBuilder = $usesBuilder;
        $this->phpDocBuilder = $phpDocBuilder;
        $this->methodParameterBuilderSkel = $methodParameterBuilderSkel;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(
        string $scope,
        string $name,
        array $returnTypes = [],
        bool $static = false,
        string $description = ''
    )
    {
        $this->setScope($scope);
        $this->setName($name);
        $this->setReturnTypes($returnTypes);
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
    public function generate(string $indent = null): ?string
    {
        if (count($this->lines) === 0) {
            return '';
        }

        $content = "\n";

        $this->configurePhpDocBuilder();
        $content .= $this->phpDocBuilder->generate($indent);

        $content .= $this->buildMethodSignature($indent);
        $content .= "\n";

        $content .= $indent . '{' . "\n";
        $content .= $this->buildMethodBody($indent);
        $content .= $indent . '}' . "\n";

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function buildMethodBody(string $indent = null): string
    {
        $content = '';
        foreach ($this->lines as $line) {
            foreach (explode("\n", $line) as $innerLine) {
                $content .= $indent . $indent . $innerLine . "\n";
            }
        }
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function buildMethodSignature(string $indent = null): string
    {
        $static = ($this->static) ? ' static ' : '';
        $content = $indent . $this->scope . $static . ' function ' . $this->name . '(';

        $parameters = [];
        foreach ($this->parameters as $methodParameterBuilder) {
            $parameters[] = $methodParameterBuilder->generate($indent);
        }
        $parametersStr = implode(',%1$s', $parameters);

        $parameterStart = '';
        $additionalIndentation = ' ';
        $parameterEnd = '';
        if (strlen($parametersStr) > $this->phpDocBuilder->getWrapOn()*0.75) {
            $additionalIndentation = "\n" . $indent . $indent;
            $parameterStart = $additionalIndentation;
            $parameterEnd = "\n" . $indent;
        }


        $content .= $parameterStart;
        $content .= sprintf(
            $parametersStr,
            $additionalIndentation
        );
        $content .= $parameterEnd;

        $content .= ')';
        if (!empty($this->returnTypes) && !in_array('mixed', $this->returnTypes)) {
            $content .= ': ' . $this->getPhpReturnType();
        }
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function configurePhpDocBuilder(): void
    {
        if ($this->hasAlreadyBeenGenerated) {
            return;
        }

        if (!empty($this->getDescription())) {
            $this->phpDocBuilder->addDescriptionLine($this->getDescription());
        }
        foreach ($this->parameters as $parameter) {
            $type = $this->phpDocBuilder::getPossibleTypesFromTypeNames([$parameter->getType(), $parameter->getValueType()]);
            $this->phpDocBuilder->addParamLine($parameter->getPhpName(), $type, $parameter->getDescription());
        }
        if (!empty($this->returnTypes) && !in_array('void', $this->returnTypes)) {
            $type = $this->phpDocBuilder::getPossibleTypesFromTypeNames($this->returnTypes);
            $this->phpDocBuilder->addReturnLine($type);
        }

        $this->hasAlreadyBeenGenerated = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnTypes(): array
    {
        return $this->returnTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpReturnType(): ?string
    {
        if (empty($this->returnTypes)) {
            return null;
        }

        $phpReturnType = '';
        if (in_array('null', $this->returnTypes)) {
            $phpReturnType = '?';
        }
        foreach ($this->returnTypes as $type) {
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
     * {@inheritDoc}
     */
    public function setReturnTypes(array $returnTypes): void
    {
        $this->returnTypes = [];
        foreach ($returnTypes as $returnType) {
            $this->addReturnType($returnType);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addReturnType(string $returnType): void
    {
        $returnType = $this->usesBuilder->guessUseOrReturnType($returnType);
        if (false === $this->hasReturnType($returnType)) {
            $this->returnTypes[] = $returnType;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasReturnType(string $returnType): bool
    {
        return false !== array_search($returnType, $this->returnTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function addParameter(MethodParameterGeneratorInterface $methodParameterBuilder): void
    {
        if (!$this->hasParameter($methodParameterBuilder)) {
            $this->setParameter($methodParameterBuilder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter(MethodParameterGeneratorInterface $methodParameterBuilder): bool
    {
        return isset($this->parameters[$methodParameterBuilder->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(MethodParameterGeneratorInterface $methodParameterBuilder): void
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
    public function getPhpDocBuilder(): PhpDocGeneratorInterface
    {
        return $this->phpDocBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhpDocBuilder(PhpDocGeneratorInterface $phpDocBuilder): void
    {
        $this->phpDocBuilder = $phpDocBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsesBuilder(): UsesGeneratorInterface
    {
        return $this->usesBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsesBuilder(UsesGeneratorInterface $usesBuilder): void
    {
        $this->usesBuilder = $usesBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodParameterBuilderSkel(): MethodParameterGeneratorInterface
    {
        return $this->methodParameterBuilderSkel;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethodParameterBuilderSkel(MethodParameterGeneratorInterface $methodParameterBuilderSkel): void
    {
        $this->methodParameterBuilderSkel = $methodParameterBuilderSkel;
    }
}
