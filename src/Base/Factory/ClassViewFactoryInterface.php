<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\Attribute\PropertyViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\ClassViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\MethodsViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\PropertiesViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\TraitsViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\UsesViewInterface;

interface ClassViewFactoryInterface
{
    /**
     * @return ClassViewInterface
     */
    public function createClassView(): ClassViewInterface;

    /**
     * @return UsesViewInterface
     */
    public function createUsesView(): UsesViewInterface;

    /**
     * @return TraitsViewInterface
     */
    public function createTraitsView(): TraitsViewInterface;

    /**
     * @return PropertiesViewInterface
     */
    public function createPropertiesView(): PropertiesViewInterface;

    /**
     * @return MethodsViewInterface
     */
    public function createMethodsView(): MethodsViewInterface;

    /**
     * @return PropertyViewInterface
     */
    public function createPropertyView(): PropertyViewInterface;

    /**
     * @return string
     */
    public function getClassViewClass(): string;

    /**
     * @param string $classViewClass
     */
    public function setClassViewClass(string $classViewClass): void;

    /**
     * @return string
     */
    public function getUsesViewClass(): string;

    /**
     * @param string $usesViewClass
     */
    public function setUsesViewClass(string $usesViewClass): void;

    /**
     * @return string
     */
    public function getTraitsViewClass(): string;

    /**
     * @param string $traitsViewClass
     */
    public function setTraitsViewClass(string $traitsViewClass): void;

    /**
     * @return string
     */
    public function getPropertiesViewClass(): string;

    /**
     * @param string $propertiesViewClass
     */
    public function setPropertiesViewClass(string $propertiesViewClass): void;

    /**
     * @return string
     */
    public function getMethodsViewClass(): string;

    /**
     * @param string $methodsViewClass
     */
    public function setMethodsViewClass(string $methodsViewClass): void;

    /**
     * @return string
     */
    public function getPropertyViewClass(): string;

    /**
     * @param string $propertyViewClass
     */
    public function setPropertyViewClass(string $propertyViewClass): void;
}