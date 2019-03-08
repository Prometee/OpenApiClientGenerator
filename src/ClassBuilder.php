<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder;

use Prometee\SwaggerClientBuilder\Method\MethodsBuilder;
use Prometee\SwaggerClientBuilder\Property\PropertiesBuilder;

class ClassBuilder implements BuilderInterface
{
    public const TYPE_CLASS = 'class';
    public const TYPE_FINALE_CLASS = 'final class';
    public const TYPE_ABSTRACT_CLASS = 'abstract class';
    public const TYPE_TRAIT = 'trait';
    public const TYPE_INTERFACE = 'interface';

    /** @var string */
    protected $builderType;

    /** @var string */
    protected $namespace;

    /** @var UsesBuilder */
    protected $usesBuilder;

    /** @var string */
    protected $className;

    /** @var string|null */
    protected $extendClassName;

    /** @var string[] */
    protected $implements;

    /** @var PropertiesBuilder */
    protected $propertiesBuilder;

    /** @var MethodsBuilder */
    protected $methodsBuilder;

    /** @var ClassTraitsBuilder */
    protected $classTraitsBuilder;

    /**
     * @param string $namespace
     * @param string $className
     * @param string|null $extendClassName
     * @param array $implements
     */
    public function __construct(string $namespace, string $className, ?string $extendClassName = null, array $implements = [])
    {
        $this->builderType = static::TYPE_CLASS;
        $this->namespace = $namespace;
        $this->className = $className;
        $this->extendClassName = $extendClassName;
        $this->implements = $implements;

        $this->usesBuilder = new UsesBuilder();
        $this->classTraitsBuilder = new ClassTraitsBuilder();
        $this->propertiesBuilder = new PropertiesBuilder();
        $this->methodsBuilder = new MethodsBuilder();
    }

    public function build(string $indent = null): ?string
    {
        $content = '';

        $content .= '<?php'."\n";
        $content .= "\n";
        $content .= 'declare(strict_types=1);'."\n";
        $content .= "\n";
        $content .= 'namespace '.$this->namespace.';'."\n";
        $content .= "\n";
        $content .= $this->usesBuilder->build($indent);
        $content .= $this->buildClassSignature()."\n";
        $content .= '{';
        $content .= $this->classTraitsBuilder->build($indent);
        $content .= $this->propertiesBuilder->build($indent);
        $content .= $this->methodsBuilder->build($indent);
        $content .= '}'."\n";

        return $content;
    }

    /**
     * @return string
     */
    public function buildClassSignature(): string
    {
        $extends = ($this->extendClassName !== null) ? ' extends '.$this->extendClassName : '';
        $implements = (!empty($this->implements)) ? ' implements '.implode(', ', $this->implements) : '';
        return $this->builderType . ' ' . $this->className.$extends.$implements;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return UsesBuilder
     */
    public function getUsesBuilder(): UsesBuilder
    {
        return $this->usesBuilder;
    }

    /**
     * @param UsesBuilder $usesBuilder
     */
    public function setUsesBuilder(UsesBuilder $usesBuilder): void
    {
        $this->usesBuilder = $usesBuilder;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getBuilderType(): string
    {
        return $this->builderType;
    }

    /**
     * @return ClassTraitsBuilder
     */
    public function getClassTraitsBuilder(): ClassTraitsBuilder
    {
        return $this->classTraitsBuilder;
    }

    /**
     * @param ClassTraitsBuilder $classTraitsBuilder
     */
    public function setClassTraitsBuilder(ClassTraitsBuilder $classTraitsBuilder): void
    {
        $this->classTraitsBuilder = $classTraitsBuilder;
    }

    /**
     * @return string|null
     */
    public function getExtendClassName(): ?string
    {
        return $this->extendClassName;
    }

    /**
     * @param string|null $extendClassName
     */
    public function setExtendClassName(?string $extendClassName): void
    {
        $this->extendClassName = $extendClassName;
    }

    /**
     * @return string[]
     */
    public function getImplements(): array
    {
        return $this->implements;
    }

    /**
     * @param string[] $implements
     */
    public function setImplements(array $implements): void
    {
        $this->implements = $implements;
    }

    /**
     * @return PropertiesBuilder
     */
    public function getPropertiesBuilder(): PropertiesBuilder
    {
        return $this->propertiesBuilder;
    }

    /**
     * @param PropertiesBuilder $propertiesBuilder
     */
    public function setPropertiesBuilder(PropertiesBuilder $propertiesBuilder): void
    {
        $this->propertiesBuilder = $propertiesBuilder;
    }

    /**
     * @return MethodsBuilder
     */
    public function getMethodsBuilder(): MethodsBuilder
    {
        return $this->methodsBuilder;
    }

    /**
     * @param MethodsBuilder $methodsBuilder
     */
    public function setMethodsBuilder(MethodsBuilder $methodsBuilder): void
    {
        $this->methodsBuilder = $methodsBuilder;
    }
}
