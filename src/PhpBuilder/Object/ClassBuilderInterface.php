<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object;

use Prometee\SwaggerClientBuilder\PhpBuilder\BuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\MethodsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\PropertiesBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\TraitsBuilderInterface;
use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other\UsesBuilderInterface;

interface ClassBuilderInterface extends BuilderInterface
{
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
    );

    /**
     * @return TraitsBuilderInterface
     */
    public function getTraitsBuilder(): TraitsBuilderInterface;

    /**
     * @param PropertiesBuilderInterface $propertiesBuilder
     */
    public function setPropertiesBuilder(PropertiesBuilderInterface $propertiesBuilder): void;

    /**
     * @param UsesBuilderInterface $usesBuilder
     */
    public function setUsesBuilder(UsesBuilderInterface $usesBuilder): void;

    /**
     * @param TraitsBuilderInterface $traitsBuilder
     */
    public function setTraitsBuilder(TraitsBuilderInterface $traitsBuilder): void;

    /**
     * @return string
     */
    public function getBuilderType(): string;

    /**
     * @return MethodsBuilderInterface
     */
    public function getMethodsBuilder(): MethodsBuilderInterface;

    /**
     * @param MethodsBuilderInterface $methodsBuilder
     */
    public function setMethodsBuilder(MethodsBuilderInterface $methodsBuilder): void;

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void;

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return string[]
     */
    public function getImplements(): array;

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return UsesBuilderInterface
     */
    public function getUsesBuilder(): UsesBuilderInterface;

    /**
     * @return string
     */
    public function buildClassSignature(): string;

    /**
     * @return string|null
     */
    public function getExtendClassName(): ?string;

    /**
     * @param string[] $implements
     */
    public function setImplements(array $implements): void;

    /**
     * @return PropertiesBuilderInterface
     */
    public function getPropertiesBuilder(): PropertiesBuilderInterface;

    /**
     * @param string|null $extendClass
     */
    public function setExtendClassName(?string $extendClass): void;

    /**
     * @param string $className
     */
    public function setClassName(string $className): void;
}