<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

use Prometee\SwaggerClientBuilder\BuilderInterface;

class MethodsBuilder implements BuilderInterface
{
    /** @var MethodBuilderInterface[] */
    protected $methods;

    public function __construct(array $methods = [])
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }
    }

    public function build(string $indent = null): ?string
    {
        $content = '';

        foreach ($this->methods as $method) {
            $content .= $method->build($indent);
        }

        return $content;
    }

    /**
     * @param MethodBuilderInterface[] $methodBuilders
     */
    public function addMultipleMethod(array $methodBuilders): void
    {
        foreach ($methodBuilders as $methodBuilder) {
            $this->addMethod($methodBuilder);
        }
    }

    public function addMethod(MethodBuilderInterface $methodBuilder)
    {
        if (!$this->hasMethod($methodBuilder->getName())) {
            $this->methods[$methodBuilder->getName()] = $methodBuilder;
        }
    }

    public function hasMethod(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    public function createConstructor(): ConstructorBuilder
    {
        $constructor = new ConstructorBuilder();
        $this->addMethod($constructor);

        return $constructor;
    }
}
