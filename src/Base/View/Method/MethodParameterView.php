<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Method;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodParameterGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\AbstractView;

/**
 * @property MethodParameterGeneratorInterface $generator
 */
class MethodParameterView extends AbstractView implements MethodParameterViewInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        parent::build($indent, $eol);

        $content = '';

        $content .= !empty($this->generator->getTypes()) ? $this->generator->getPhpType() . ' ' : '';
        $content .= $this->generator->isByReference() ? '&' : '';
        $content .= $this->generator->getPhpName();
        $content .= ($this->generator->getValue() !== null) ? ' = ' . $this->generator->getValue() : '';

        return $content;
    }
}