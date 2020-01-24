<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;

class ClassBuilder implements ClassBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;
    /** @var PropertiesBuilderInterface */
    protected $propertiesBuilder;
    /** @var MethodsBuilderInterface */
    protected $methodsBuilder;
    /** @var TraitsBuilderInterface */
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
     * @param UsesBuilderInterface $usesBuilder
     * @param PropertiesBuilderInterface $propertiesBuilder
     * @param MethodsBuilderInterface $methodsBuilder
     * @param TraitsBuilderInterface $traitsBuilder
     */
    public function __construct(
        UsesBuilderInterface $usesBuilder,
        PropertiesBuilderInterface $propertiesBuilder,
        MethodsBuilderInterface $methodsBuilder,
        TraitsBuilderInterface $traitsBuilder
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
    public function build(string $indent = null): ?string
    {
        $content = '';

        $content .= '<?php' . "\n";
        $content .= "\n";
        $content .= 'declare(strict_types=1);' . "\n";
        $content .= "\n";
        $content .= 'namespace ' . $this->namespace . ';' . "\n";
        $content .= "\n";
        $content .= $this->usesBuilder->build($indent);
        $content .= $this->buildClassSignature() . "\n";
        $content .= '{';
        if (null !== $this->traitsBuilder) {
            $content .= $this->traitsBuilder->build($indent);
        }
        $content .= $this->propertiesBuilder->build($indent);
        $content .= $this->methodsBuilder->build($indent);
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
    public function getUsesBuilder(): UsesBuilderInterface
    {
        return $this->usesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesBuilder(UsesBuilderInterface $usesBuilder): void
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
    public function getTraitsBuilder(): TraitsBuilderInterface
    {
        return $this->traitsBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsBuilder(TraitsBuilderInterface $traitsBuilder): void
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
    public function getPropertiesBuilder(): PropertiesBuilderInterface
    {
        return $this->propertiesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesBuilder(PropertiesBuilderInterface $propertiesBuilder): void
    {
        $this->propertiesBuilder = $propertiesBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsBuilder(): MethodsBuilderInterface
    {
        return $this->methodsBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsBuilder(MethodsBuilderInterface $methodsBuilder): void
    {
        $this->methodsBuilder = $methodsBuilder;
    }
}
