<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\Method\MethodParameterViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Method\MethodViewInterface;

interface MethodViewFactoryInterface
{
    /**
     * @return MethodViewInterface
     */
    public function createMethodView(): MethodViewInterface;

    /**
     * @return MethodParameterViewInterface
     */
    public function createMethodParameterView(): MethodParameterViewInterface;

    /**
     * @param string $methodViewClass
     */
    public function setMethodViewClass(string $methodViewClass): void;

    /**
     * @return string
     */
    public function getMethodViewClass(): string;

    /**
     * @return string
     */
    public function getMethodParameterClass(): string;

    /**
     * @param string $methodParameter
     */
    public function setMethodParameterClass(string $methodParameter): void;
}