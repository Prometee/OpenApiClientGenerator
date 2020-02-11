<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\Generator\PhpDoc;

use Prometee\SwaggerClientGenerator\Base\Generator\AbstractGenerator;
use Prometee\SwaggerClientGenerator\Base\View\PhpDoc\PhpDocViewInterface;

class PhpDocGenerator extends AbstractGenerator implements PhpDocGeneratorInterface
{
    /** @var array */
    protected $lines = [];
    /** @var int */
    protected $wrapOn = self::DEFAULT_WRAP_ON;


    /**
     * @param PhpDocViewInterface $phpDocView
     */
    public function __construct(
        PhpDocViewInterface $phpDocView
    )
    {
        $this->setView($phpDocView);
    }

    /**
     * {@inheritDoc}
     */
    public function configure(array $lines = [], ?int $wrapOn = null): void
    {
        $this->lines = $lines;
        $this->wrapOn = $wrapOn ?? self::DEFAULT_WRAP_ON;
    }

    /**
     * {@inheritDoc}
     */
    public function orderLines(callable $orderingCallable): void
    {
        uksort($this->lines, $orderingCallable);
    }

    /**
     * {@inheritDoc}
     */
    public function addLine(string $line, string $type = ''): void
    {
        if (!isset($this->lines[$type])) {
            $this->lines[$type] = [];
        }

        $this->lines[$type][] = $line;
    }

    /**
     * {@inheritDoc}
     */
    public function addDescriptionLine(string $line): void
    {
        $this->addLine($line, static::TYPE_DESCRIPTION);
    }

    /**
     * {@inheritDoc}
     */
    public function addEmptyLine(): void
    {
        $this->addDescriptionLine('');
    }

    /**
     * {@inheritDoc}
     */
    public function addVarLine(?string $line): void
    {
        $this->addLine($line, static::TYPE_VAR);
    }

    /**
     * {@inheritDoc}
     */
    public function addParamLine(string $name, string $type = '', string $description = ''): void
    {
        $line = sprintf('%s %s %s', $type, $name, $description);
        $this->addLine(
            trim($line),
            static::TYPE_PARAM
        );
    }

    /**
     * {@inheritDoc}
     */
    public function addReturnLine(string $line): void
    {
        if (empty($line)) {
            return;
        }

        $this->addLine($line, static::TYPE_RETURN);
    }

    /**
     * {@inheritDoc}
     */
    public function addThrowsLine(string $line): void
    {
        $this->addLine($line, static::TYPE_THROWS);
    }

    /**
     * {@inheritDoc}
     */
    public function hasSingleVarLine(): bool
    {
        return isset($this->lines[static::TYPE_VAR])
            && count($this->lines) === 1
            && count($this->lines[static::TYPE_VAR]) === 1;
    }

    /**
     * {@inheritDoc}
     */
    public function setWrapOn(int $wrapOn): void
    {
        $this->wrapOn = $wrapOn;
    }

    /**
     * {@inheritDoc}
     */
    public function getWrapOn(): int
    {
        return $this->wrapOn;
    }

    /**
     * {@inheritDoc}
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * {@inheritDoc}
     */
    public function setLines(array $lines): void
    {
        $this->lines = $lines;
    }
}
