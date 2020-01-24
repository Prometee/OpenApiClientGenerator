<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\PhpBuilder\Object\Method;

class IsserSetterBuilder extends GetterSetterBuilder implements IsserSetterBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            $this->getterMethodBuilder->configure(
                MethodBuilderInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::ISSER_PREFIX),
                $this->propertyBuilder->getTypes()
            );

            $this->getterMethodBuilder->addLine(
                sprintf('return $this->%s;', $this->propertyBuilder->getName())
            );
        }
    }
}
