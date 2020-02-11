<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\Method\MethodParameterViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodViewInterface;

class MethodViewFactory implements MethodViewFactoryInterface
{
    /** @var string */
    protected $methodViewClass;

    /** @var string */
    protected $methodParameterClass;

    /**
     * {@inheritDoc}
     */
    public function createMethodView(): MethodViewInterface
    {
        return new $this->methodViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodParameterView(): MethodParameterViewInterface
    {
        return new $this->methodParameterClass();
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodViewClass(string $methodViewClass): void
    {
        $this->methodViewClass = $methodViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodViewClass(): string
    {
        return $this->methodViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodParameterClass(): string
    {
        return $this->methodParameterClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodParameterClass(string $methodParameterClass): void
    {
        $this->methodParameterClass = $methodParameterClass;
    }
}