<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Factory;

use Prometee\SwaggerClientGenerator\Base\View\Attribute\PropertyViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\ClassViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\MethodsViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\PropertiesViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\TraitsViewInterface;
use Prometee\SwaggerClientGenerator\Base\View\Other\UsesViewInterface;

class ClassViewFactory implements ClassViewFactoryInterface
{
    /** @var string */
    protected $classViewClass;
    /** @var string */
    protected $usesViewClass;
    /** @var string */
    protected $traitsViewClass;
    /** @var string */
    protected $propertiesViewClass;
    /** @var string */
    protected $methodsViewClass;
    /** @var string */
    protected $propertyViewClass;

    /**
     * {@inheritDoc}
     */
    public function createClassView(): ClassViewInterface
    {
        return new $this->classViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createUsesView(): UsesViewInterface
    {
        return new $this->usesViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createTraitsView(): TraitsViewInterface
    {
        return new $this->traitsViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertiesView(): PropertiesViewInterface
    {
        return new $this->propertiesViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createMethodsView(): MethodsViewInterface
    {
        return new $this->methodsViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function createPropertyView(): PropertyViewInterface
    {
        return new $this->propertyViewClass();
    }

    /**
     * {@inheritDoc}
     */
    public function getClassViewClass(): string
    {
        return $this->classViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setClassViewClass(string $classViewClass): void
    {
        $this->classViewClass = $classViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsesViewClass(): string
    {
        return $this->usesViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsesViewClass(string $usesViewClass): void
    {
        $this->usesViewClass = $usesViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getTraitsViewClass(): string
    {
        return $this->traitsViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setTraitsViewClass(string $traitsViewClass): void
    {
        $this->traitsViewClass = $traitsViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesViewClass(): string
    {
        return $this->propertiesViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertiesViewClass(string $propertiesViewClass): void
    {
        $this->propertiesViewClass = $propertiesViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodsViewClass(): string
    {
        return $this->methodsViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setMethodsViewClass(string $methodsViewClass): void
    {
        $this->methodsViewClass = $methodsViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertyViewClass(): string
    {
        return $this->propertyViewClass;
    }

    /**
     * {@inheritDoc}
     */
    public function setPropertyViewClass(string $propertyViewClass): void
    {
        $this->propertyViewClass = $propertyViewClass;
    }
}