<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\Model\Attribute;

use Prometee\SwaggerClientBuilder\PhpBuilder\Object\Attribute\PropertyBuilderInterface;

interface ModelPropertyBuilderInterface extends PropertyBuilderInterface
{
    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void;
}