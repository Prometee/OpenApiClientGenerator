<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method;

class ConstructorBuilder extends MethodBuilder implements ConstructorBuilderInterface
{
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
        parent::configure('public', '__construct', $returnType, $static, $description);
    }
}
