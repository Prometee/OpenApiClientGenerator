<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Model\Attribute;

use Prometee\SwaggerClientGenerator\Base\Generator\Object\Attribute\PropertyGeneratorInterface;

interface ModelPropertyGeneratorInterface extends PropertyGeneratorInterface
{
    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void;

    /**
     * @return bool
     */
    public function isInherited(): bool;

    /**
     * @param bool $inherited
     */
    public function setInherited(bool $inherited): void;
}