<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object;

use Prometee\SwaggerClientGenerator\Base\Generator\GeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Object\Other\UsesGeneratorInterface;

interface ClassGeneratorInterface extends GeneratorInterface
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
     * @return TraitsGeneratorInterface
     */
    public function getTraitsGenerator(): TraitsGeneratorInterface;

    /**
     * @param PropertiesGeneratorInterface $propertiesGenerator
     */
    public function setPropertiesGenerator(PropertiesGeneratorInterface $propertiesGenerator): void;

    /**
     * @param UsesGeneratorInterface $usesGenerator
     */
    public function setUsesGenerator(UsesGeneratorInterface $usesGenerator): void;

    /**
     * @param TraitsGeneratorInterface $traitsGenerator
     */
    public function setTraitsGenerator(TraitsGeneratorInterface $traitsGenerator): void;

    /**
     * @return string
     */
    public function getGeneratorType(): string;

    /**
     * @return MethodsGeneratorInterface
     */
    public function getMethodsGenerator(): MethodsGeneratorInterface;

    /**
     * @param MethodsGeneratorInterface $methodsGenerator
     */
    public function setMethodsGenerator(MethodsGeneratorInterface $methodsGenerator): void;

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
     * @return UsesGeneratorInterface
     */
    public function getUsesGenerator(): UsesGeneratorInterface;

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
     * @return PropertiesGeneratorInterface
     */
    public function getPropertiesGenerator(): PropertiesGeneratorInterface;

    /**
     * @param string|null $extendClass
     */
    public function setExtendClassName(?string $extendClass): void;

    /**
     * @param string $className
     */
    public function setClassName(string $className): void;
}