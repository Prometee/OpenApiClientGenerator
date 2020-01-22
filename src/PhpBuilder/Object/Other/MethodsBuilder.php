<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Other;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method\MethodBuilderInterface;

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

        $this->orderMethods();
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
    public function orderMethods(): void
    {
        uksort($this->methods, function ($k1, $k2) {
            $o1 = preg_match('#^__#', $k1) === 0 ? 1 : 0;
            $o2 = preg_match('#^__#', $k2) === 0 ? 1 : 0;

            return $o1 - $o2;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function hasMethod(string $name): bool
    {
        return isset($this->methods[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
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
}
