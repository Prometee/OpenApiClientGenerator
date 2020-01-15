<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilderInterface;

class MethodsBuilder implements MethodsBuilderInterface
{
    /** @var UsesBuilderInterface */
    protected $usesBuilder;
    /** @var MethodBuilderInterface[] */
    protected $methods = [];

    /**
     * {@inheritDoc}
     */
    public function configure(UsesBuilderInterface $usesBuilder, array $methods = []): void
    {
        $this->usesBuilder = $usesBuilder;
        $this->methods = $methods;
    }

    /**
     * {@inheritDoc}
     */
    public function build(string $indent = null): ?string
    {
        $content = '';

        foreach ($this->methods as $method) {
            $content .= $method->build($indent);
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function addMultipleMethod(array $methodBuilders): void
    {
        foreach ($methodBuilders as $methodBuilder) {
            $this->addMethod($methodBuilder);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addMethod(MethodBuilderInterface $methodBuilder): void
    {
        if (!$this->hasMethod($methodBuilder->getName())) {
            $this->methods[$methodBuilder->getName()] = $methodBuilder;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasMethod(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    /**
     * @return MethodBuilderInterface[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param MethodBuilderInterface[] $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }
}
