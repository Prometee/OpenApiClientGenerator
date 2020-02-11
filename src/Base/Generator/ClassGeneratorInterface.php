<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

use Prometee\SwaggerClientGenerator\Base\Generator\Other\MethodsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\PropertiesGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\TraitsGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\Generator\Other\UsesGeneratorInterface;

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
     * @return string|null
     */
    public function getExtendClassName(): ?string;

    /**
     * @param string|null $extendClassName
     */
    public function setExtendClassName(?string $extendClassName): void;

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
    public function setExtendClass(?string $extendClass): void;

    /**
     * @param string $className
     */
    public function setClassName(string $className): void;
}