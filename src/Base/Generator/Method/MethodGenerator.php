<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Method;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc\PhpDocGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodViewInterface;

class MethodGenerator extends AbstractGenerator implements MethodGeneratorInterface
{
    /** @var PhpDocGeneratorInterface */
    protected $phpDocGenerator;
    /** @var UsesGeneratorInterface */
    protected $usesGenerator;
    /** @var MethodParameterGeneratorInterface */
    protected $methodParameterGeneratorSkel;

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
     * @param MethodViewInterface $methodView
     * @param UsesGeneratorInterface $usesGenerator
     * @param PhpDocGeneratorInterface $phpDocGenerator
     * @param MethodParameterGeneratorInterface $methodParameterGeneratorSkel
     */
    public function __construct(
        MethodViewInterface $methodView,
        UsesGeneratorInterface $usesGenerator,
        PhpDocGeneratorInterface $phpDocGenerator,
        MethodParameterGeneratorInterface $methodParameterGeneratorSkel
    )
    {
        $this->setView($methodView);
        $this->usesGenerator = $usesGenerator;
        $this->phpDocGenerator = $phpDocGenerator;
        $this->methodParameterGeneratorSkel = $methodParameterGeneratorSkel;
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

        $this->phpDocGenerator->configure();
        $this->hasAlreadyBeenGenerated = false;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null, string $eol = null): ?string
    {
        $this->configurePhpDocGenerator();
        return parent::generate($indent, $eol);
    }

    /**
     * {@inheritdoc}
     */
    public function configurePhpDocGenerator(): void
    {
        if ($this->hasAlreadyBeenGenerated) {
            return;
        }

        if (!empty($this->getDescription())) {
            $this->phpDocGenerator->addDescriptionLine($this->getDescription());
        }
        foreach ($this->parameters as $parameter) {
            $this->phpDocGenerator->addParamLine($parameter->getPhpName(), $parameter->getType(), $parameter->getDescription());
        }
        if (!empty($this->returnTypes) && !in_array('void', $this->returnTypes)) {
            $this->phpDocGenerator->addReturnLine($this->getReturnType());
        }

        $this->hasAlreadyBeenGenerated = true;
    }

    /**
     * {@inheritDoc}
     */
    public function getReturnType(): string
    {
        return implode('|', $this->returnTypes);
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
        $returnType = $this->usesGenerator->guessUseOrReturnType($returnType);
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
    public function addParameter(MethodParameterGeneratorInterface $methodParameterGenerator): void
    {
        if (!$this->hasParameter($methodParameterGenerator)) {
            $this->setParameter($methodParameterGenerator);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter(MethodParameterGeneratorInterface $methodParameterGenerator): bool
    {
        return isset($this->parameters[$methodParameterGenerator->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(MethodParameterGeneratorInterface $methodParameterGenerator): void
    {
        $this->parameters[$methodParameterGenerator->getName()] = $methodParameterGenerator;
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

    /**
     * {@inheritdoc}
     */
    public function getUsesGenerator(): UsesGeneratorInterface
    {
        return $this->usesGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void
    {
        $this->usesGenerator = $usesGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodParameterGeneratorSkel(): MethodParameterGeneratorInterface
    {
        return $this->methodParameterGeneratorSkel;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethodParameterGeneratorSkel(MethodParameterGeneratorInterface $methodParameterGeneratorSkel): void
    {
        $this->methodParameterGeneratorSkel = $methodParameterGeneratorSkel;
    }
}
