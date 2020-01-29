<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute;

class ConstantGenerator extends PropertyGenerator implements ConstantGeneratorInterface
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
