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
    protected $usesGenerator;
    /** @var PropertiesGeneratorInterface */
    protected $propertiesGenerator;
    /** @var MethodsGeneratorInterface */
    protected $methodsGenerator;
    /** @var TraitsGeneratorInterface */
    protected $traitsGenerator;

    /** @var string */
    protected $generatorType = 'class';
    /** @var string */
    protected $namespace;
    /** @var string */
    protected $className;
    /** @var string|null */
    protected $extendClassName;
    /** @var string[] */
    protected $implements;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     * @param PropertiesGeneratorInterface $propertiesGenerator
     * @param MethodsGeneratorInterface $methodsGenerator
     * @param TraitsGeneratorInterface $traitsGenerator
     */
    public function __construct(
        UsesGeneratorInterface $usesGenerator,
        PropertiesGeneratorInterface $propertiesGenerator,
        MethodsGeneratorInterface $methodsGenerator,
        TraitsGeneratorInterface $traitsGenerator
    )
    {
        $this->usesGenerator = $usesGenerator;
        $this->propertiesGenerator = $propertiesGenerator;
        $this->methodsGenerator = $methodsGenerator;
        $this->traitsGenerator = $traitsGenerator;
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

        $this->usesGenerator->configure($this->namespace);
        $this->traitsGenerator->configure($this->usesGenerator);
        $this->propertiesGenerator->configure($this->usesGenerator);
        $this->methodsGenerator->configure($this->usesGenerator);
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null): ?string
    {
        $content = '';

        $content .= '<?php' . "\n";
        $content .= "\n";
        $content .= 'declare(strict_types=1);'."\n";
        $content .= "\n";
        $content .= 'namespace '.$this->namespace.';'."\n";
        $content .= "\n";
        $content .= $this->usesGenerator->generate($indent);
        $content .= $this->buildClassSignature() . "\n";
        $content .= '{';
        if (null !== $this->traitsGenerator) {
            $content .= $this->traitsGenerator->generate($indent);
        }
        $content .= $this->propertiesGenerator->generate($indent);
        $content .= $this->methodsGenerator->generate($indent);
        $content .= '}'."\n";

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function buildClassSignature(): string
    {
        $extends = ($this->extendClassName !== null) ? ' extends '.$this->extendClassName : '';
        $implements = (!empty($this->implements)) ? ' implements '.implode(', ', $this->implements) : '';

        return $this->generatorType.' '.$this->className.$extends.$implements;
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
    public function getUsesGenerator(): UsesGeneratorInterface
    {
        return $this->usesGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void
    {
        $this->usesGenerator = $usesGenerator;
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
    public function getGeneratorType(): string
    {
        return $this->generatorType;
    }

    /**
     * {@inheritDoc}
     */
    public function getTraitsGenerator(): TraitsGeneratorInterface
    {
        return $this->traitsGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsGenerator(TraitsGeneratorInterface $traitsGenerator): void
    {
        $this->traitsGenerator = $traitsGenerator;
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

        $this->usesGenerator->guessUse($extendClass);
        $this->extendClassName = $this->usesGenerator->getInternalUseName($extendClass);
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
            $this->usesGenerator->guessUse($implement);
            $internalImplements[] = $this->usesGenerator->getInternalUseName($implement);
        }
        $this->implements = $internalImplements;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesGenerator(): PropertiesGeneratorInterface
    {
        return $this->propertiesGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesGenerator(PropertiesGeneratorInterface $propertiesGenerator): void
    {
        $this->propertiesGenerator = $propertiesGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsGenerator(): MethodsGeneratorInterface
    {
        return $this->methodsGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsGenerator(MethodsGeneratorInterface $methodsGenerator): void
    {
        $this->methodsGenerator = $methodsGenerator;
    }
}
