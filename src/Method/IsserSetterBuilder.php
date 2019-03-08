<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Method;

class IsserSetterBuilder extends GetterSetterBuilder
{
    const ISSER_PREFIX = 'is';

    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            $this->getterMethod = new MethodBuilder(
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::ISSER_PREFIX),
                $this->propertyBuilder->getPhpType()
            );

            $this->getterMethod->addLine(
                sprintf('return $this->%s;', $this->propertyBuilder->getName())
            );
        }
    }
}
