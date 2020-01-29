<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

class ConstructorGenerator extends MethodGenerator implements ConstructorGeneratorInterface
{
    /** @var {@inheritDoc} */
    protected $scope = self::SCOPE_PUBLIC;
    /** @var {@inheritDoc} */
    protected $name = '__construct';

    /**
     * {@inheritDoc}
     */
    public function configure(
        string $scope,
        string $name,
        array $returnTypes = [],
        bool $static = false,
        string $description = ''
    )
    {
        parent::configure(self::SCOPE_PUBLIC, '__construct', $returnTypes, $static, $description);
    }
}
