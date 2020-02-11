<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Attribute;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\Attribute\PropertyGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\AbstractView;

/**
 * @property PropertyGeneratorInterface $generator
 */
class PropertyView extends AbstractView implements PropertyViewInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        parent::build($indent, $eol);

        $phpDoc = $this->generator->getPhpDocGenerator()->generate($indent, $eol);

        $value = '';
        if (null !== $this->generator->getValue()) {
            $value = sprintf(' = %s', $this->generator->getValue());
        }

        $format = '%1$s%3$s%2$s%4$s %5$s%6$s;%1$s';

        return sprintf(
            $format,
            $this->eol,
            $this->indent,
            $phpDoc,
            $this->generator->getScope(),
            $this->generator->getPhpName(),
            $value
        );
    }
}