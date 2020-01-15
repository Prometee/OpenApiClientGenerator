<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

class ConstructorBuilder extends MethodBuilder implements ConstructorBuilderInterface
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
        ?string $returnType = null,
        bool $static = false,
        string $description = ''
    )
    {
        parent::configure(self::SCOPE_PUBLIC, '__construct', $returnType, $static, $description);
    }
}
