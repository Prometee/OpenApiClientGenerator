<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

class ConstructorBuilder extends MethodBuilder
{
    public function __construct()
    {
        parent::__construct('public', '__construct');
    }
}
