<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute;

class ConstantBuilder extends PropertyBuilder implements ConstantBuilderInterface
{
    /** {@inheritDoc} */
    protected $scope = 'public const';

    /**
     * {@inheritDoc}
     */
    public function getPhpName(): string
    {
        return strtoupper($this->name);
    }
}
