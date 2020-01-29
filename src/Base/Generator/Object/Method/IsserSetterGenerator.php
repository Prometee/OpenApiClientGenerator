<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\Object\Method;

class IsserSetterGenerator extends GetterSetterGenerator implements IsserSetterGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function configureGetter(string $indent = null): void
    {
        if (!$this->isWriteOnly()) {
            $this->getterMethodBuilder->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::ISSER_PREFIX),
                $this->propertyBuilder->getTypes()
            );

            $this->getterMethodBuilder->addLine(
                sprintf('return $this->%s;', $this->propertyBuilder->getName())
            );
        }
    }
}
