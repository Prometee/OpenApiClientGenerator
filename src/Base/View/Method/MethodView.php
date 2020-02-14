<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View\Method;

use Exception;
use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGeneratorInterface;
use Prometee\SwaggerClientGenerator\Base\View\AbstractView;

/**
 * @property MethodGeneratorInterface $generator
 */
class MethodView extends AbstractView implements MethodViewInterface
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

        if (null === $phpDoc && empty($this->generator->getLines())) {
            return null;
        }

        return sprintf('%1$s%3$s%4$s%1$s%2$s{%1$s%5$s%2$s}%1$s',
            $this->eol,
            $this->indent,
            $phpDoc,
            $this->buildMethodSignature($indent),
            $this->buildMethodBody($indent)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildMethodBody(string $indent = null): string
    {
        $content = '';
        foreach ($this->generator->getLines() as $line) {
            foreach (explode("\n", $line) as $innerLine) {
                $content .= $indent.$indent.$innerLine. "\n";
            }
        }
        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function buildMethodSignature(string $indent = null): string
    {
        $static = ($this->generator->isStatic()) ? ' static ' : '';

        // result example : "string $first,%1$sstring $second,%1$sstring $third"
        $methodParameters = $this->buildMethodParameters($indent, '%1$s');
        // 3 = length of $formatVar - 1 (see line just below)
        // -1 because the first parameter don't have $formatVar
        $methodParametersLength = strlen($methodParameters) - 3*(count($this->generator->getParameters())-1);
        $parametersFutureFormat = '%s%s%s';
        $content = sprintf('%s%s%s function %s(%s)%s',
            $indent,
            $this->generator->getScope(),
            $static,
            $this->generator->getName(),
            $parametersFutureFormat,
            $this->buildReturnType($indent)
        );

        $parametersStart = '';
        $additionalIndentation = ' ';
        $parametersEnd = '';
        $contentLength = strlen($content) - strlen($parametersFutureFormat) + $methodParametersLength;
        if ($contentLength > $this->generator->getPhpDocGenerator()->getWrapOn()) {
            // Make parameters go into multiline formation
            $additionalIndentation = "\n".$indent.$indent;
            $parametersStart = $additionalIndentation;
            $parametersEnd = "\n".$indent;
        }

        return sprintf($content,
            $parametersStart,
            sprintf($methodParameters, $additionalIndentation),
            $parametersEnd
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildMethodParameters(string $indent = null, string $formatVar = ' '): string
    {
        $parameters = [];

        foreach ($this->generator->getParameters() as $methodParameterGenerator) {
            $parameters[] = $methodParameterGenerator->generate($indent);
        }

        return implode(sprintf(',%s', $formatVar), $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function buildReturnType(string $indent = null): string
    {
        if (empty($this->generator->getReturnTypes())) {
            return '';
        }

        if (in_array('mixed', $this->generator->getReturnTypes())) {
            return '';
        }

        return sprintf (': %s', $this->generator->getPhpTypeFromReturnTypes());
    }
}