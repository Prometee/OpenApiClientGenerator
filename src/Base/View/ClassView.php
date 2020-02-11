<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\ClassGeneratorInterface;

/**
 * @property ClassGeneratorInterface $generator
 */
class ClassView extends AbstractView implements ClassViewInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function build(string $indent = null, string $eol = null): ?string
    {
        parent::build($indent, $eol);

        $body = $this->buildBody();

        $format = '<?php%1$s';
        $format .= '%1$s';
        $format .= 'declare(strict_types=1);%1$s';
        $format .= '%1$s';
        $format .= 'namespace %2$s;%1$s';
        $format .= '%1$s';
        $format .= '%3$s';
        $format .= '%4$s%1$s';
        $format .= '{';
        $format .= '%5$s';
        $format .= '}%1$s';

        return sprintf(
            $format,
            $this->eol,
            $this->generator->getNamespace(),
            $this->generator->getUsesGenerator()->generate($indent, $eol),
            $this->buildSignature(),
            $body
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildBody(): string
    {
        $body = sprintf('%s%s%s',
            $this->generator->getTraitsGenerator()->generate($this->indent, $this->eol),
            $this->generator->getPropertiesGenerator()->generate($this->indent, $this->eol),
            $this->generator->getMethodsGenerator()->generate($this->indent, $this->eol)
        );

        if (empty($body)) {
            $body = $this->eol;
        }

        return $body;
    }

    /**
     * {@inheritDoc}
     */
    public function buildSignature(): ?string
    {
        $extendClassName = $this->generator->getExtendClassName();
        $extends = ($extendClassName !== null) ? ' extends '.$extendClassName : '';

        $implements = '';
        if (count($this->generator->getImplements()) > 0) {
            $implements = ' implements '.implode(', ', $this->generator->getImplements());
        }

        return sprintf(
            '%s %s%s%s',
            $this->generator->getGeneratorType(),
            $this->generator->getClassName(),
            $extends,
            $implements
        );
    }
}