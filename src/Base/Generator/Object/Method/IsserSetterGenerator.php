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
            $this->getterMethodGenerator->configure(
                MethodGeneratorInterface::SCOPE_PUBLIC,
                $this->getMethodName(static::ISSER_PREFIX),
                $this->propertyGenerator->getTypes()
            );

            $this->getterMethodGenerator->addLine(
                sprintf('return $this->%s;', $this->propertyGenerator->getName())
            );
        }
    }
}
