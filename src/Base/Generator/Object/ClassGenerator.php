<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

class ClassGenerator implements ClassGeneratorInterface
{
    /** @var UsesGeneratorInterface */
    protected $usesBuilder;
    /** @var PropertiesGeneratorInterface */
    protected $propertiesBuilder;
    /** @var MethodsGeneratorInterface */
    protected $methodsBuilder;
    /** @var TraitsGeneratorInterface */
    protected $traitsBuilder;

    /** @var string */
    protected $builderType = 'class';
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $className;
    /** @var string|null */
    protected $extendClassName;
    /** @var string[] */
    protected $implements;

    /**
     * @param UsesGeneratorInterface $usesBuilder
     * @param PropertiesGeneratorInterface $propertiesBuilder
     * @param MethodsGeneratorInterface $methodsBuilder
     * @param TraitsGeneratorInterface $traitsBuilder
     */
    public function __construct(
        UsesGeneratorInterface $usesBuilder,
        PropertiesGeneratorInterface $propertiesBuilder,
        MethodsGeneratorInterface $methodsBuilder,
        TraitsGeneratorInterface $traitsBuilder
    )
    {
        $this->usesBuilder = $usesBuilder;
        $this->propertiesBuilder = $propertiesBuilder;
        $this->methodsBuilder = $methodsBuilder;
        $this->traitsBuilder = $traitsBuilder;
    }

    /**
     * @param string $namespace
     * @param string $className
     * @param string|null $extendClass
     * @param string[] $implements
     */
    public function configure(
        string $namespace,
        string $className,
        ?string $extendClass = null,
        array $implements = []
    )
    {
        $this->setNamespace($namespace);
        $this->setClassName($className);
        $this->setExtendClassName($extendClass);
        $this->setImplements($implements);

        $this->usesBuilder->configure($this->namespace);
        $this->traitsBuilder->configure($this->usesBuilder);
        $this->propertiesBuilder->configure($this->usesBuilder);
        $this->methodsBuilder->configure($this->usesBuilder);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null): ?string
    {
        $content = '';

        $content .= '<?php' . "\n";
        $content .= "\n";
        $content .= 'declare(strict_types=1);' . "\n";
        $content .= "\n";
        $content .= 'namespace ' . $this->namespace . ';' . "\n";
        $content .= "\n";
        $content .= $this->usesBuilder->generate($indent);
        $content .= $this->buildClassSignature() . "\n";
        $content .= '{';
        if (null !== $this->traitsBuilder) {
            $content .= $this->traitsBuilder->generate($indent);
        }
        $content .= $this->propertiesBuilder->generate($indent);
        $content .= $this->methodsBuilder->generate($indent);
        $content .= '}' . "\n";

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function buildClassSignature(): string
    {
        $extends = ($this->extendClassName !== null) ? ' extends ' . $this->extendClassName : '';
        $implements = (!empty($this->implements)) ? ' implements ' . implode(', ', $this->implements) : '';

        return $this->builderType . ' ' . $this->className . $extends . $implements;
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsesBuilder(): UsesGeneratorInterface
    {
        return $this->usesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesBuilder(UsesGeneratorInterface $usesBuilder): void
    {
        $this->usesBuilder = $usesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * {@inheritDoc}
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * {@inheritDoc}
     */
    public function getBuilderType(): string
    {
        return $this->builderType;
    }

    /**
     * {@inheritDoc}
     */
    public function getTraitsBuilder(): TraitsGeneratorInterface
    {
        return $this->traitsBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsBuilder(TraitsGeneratorInterface $traitsBuilder): void
    {
        $this->traitsBuilder = $traitsBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendClassName(): ?string
    {
        return $this->extendClassName;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtendClassName(?string $extendClass): void
    {
        if (null === $extendClass) {
            $this->extendClassName = null;
            return;
        }

        $this->usesBuilder->guessUse($extendClass);
        $this->extendClassName = $this->usesBuilder->getInternalUseName($extendClass);
    }

    /**
     * {@inheritDoc}
     */
    public function getImplements(): array
    {
        return $this->implements;
    }

    /**
     * {@inheritDoc}
     */
    public function setImplements(array $implements): void
    {
        $internalImplements = [];
        foreach ($implements as $implement) {
            $this->usesBuilder->guessUse($implement);
            $internalImplements[] = $this->usesBuilder->getInternalUseName($implement);
        }
        $this->implements = $internalImplements;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesBuilder(): PropertiesGeneratorInterface
    {
        return $this->propertiesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesBuilder(PropertiesGeneratorInterface $propertiesBuilder): void
    {
        $this->propertiesBuilder = $propertiesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsBuilder(): MethodsGeneratorInterface
    {
        return $this->methodsBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsBuilder(MethodsGeneratorInterface $methodsBuilder): void
    {
        $this->methodsBuilder = $methodsBuilder;
    }
}
