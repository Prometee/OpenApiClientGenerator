<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator;

use Prometee\SwaggerClientGenerator\Base\View\ViewInterface;

abstract class AbstractGenerator implements GeneratorInterface
{
    /** @var ViewInterface */
    protected $view;

    /**
     * {@inheritDoc}
     */
    public function getView(): ViewInterface
    {
        return $this->view;
    }

    /**
     * {@inheritDoc}
     */
    public function setView(ViewInterface $view): void
    {
        $view->setGenerator($this);
        $this->view = $view;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $indent = null, string $eol = null): ?string
    {
        return $this->view->build($indent, $eol);
    }

    public function __clone()
    {
        $this->setView(clone $this->view);
    }

    /**
     * @param string|null $value
     *
     * @return string|null
     */
    public static function getValueType(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value === '[]') {
            return 'array';
        }

        if (preg_match('#^[\'"].*[\'"]$#', $value)) {
            return 'string';
        }

        return self::getValueNumericType($value);
    }

    /**
     * @param string $value
     *
     * @return string|null
     */
    public static function getValueNumericType(string $value): ?string
    {
        if (in_array($value, ['true', 'false'])) {
            return 'bool';
        }

        if (preg_match('#^[0-9]+$#', $value)) {
            return 'int';
        }

        if (preg_match('#^[0-9\.]+$#', $value)) {
            return 'float';
        }

        return null;
    }

    /**
     * @param string[] $types
     *
     * @return string|null
     */
    public static function getPhpType(array $types): ?string
    {
        if (empty($types)) {
            return null;
        }

        $phpType = '';
        if (in_array('null', $types)) {
            $phpType = '?';
        }
        foreach ($types as $type) {
            if (preg_match('#\[\]$#', $type)) {
                $phpType .= 'array';
                break;
            }
            if ($type !== 'null') {
                $phpType .= $type;
                break;
            }
        }

        return $phpType;
    }
}