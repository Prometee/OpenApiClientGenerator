<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

class SwaggerGenerator implements SwaggerGeneratorInterface
{
    /** @var SwaggerModelGeneratorInterface */
    protected $modelGenerator;
    /** @var SwaggerOperationsGeneratorInterface */
    protected $operationsGenerator;

    /** @var string */
    protected $swaggerUri = '';
    /** @var string */
    protected $folder = '';
    /** @var string */
    protected $namespace = '';
    /** @var array */
    protected $definitions = [];
    /** @var string */
    protected $indent = '    ';
    /** @var bool */
    protected $override = false;

    /**
     * @param SwaggerModelGeneratorInterface $modelGenerator
     * @param SwaggerOperationsGeneratorInterface $operationsGenerator
     */
    public function __construct(
        SwaggerModelGeneratorInterface $modelGenerator,
        SwaggerOperationsGeneratorInterface $operationsGenerator
    )
    {
        $this->modelGenerator = $modelGenerator;
        $this->operationsGenerator = $operationsGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(
        string $swaggerUri,
        string $folder,
        string $namespace,
        string $indent = '    ',
        bool $override = false
    ): void
    {
        $this->swaggerUri = $swaggerUri;
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->indent = $indent;
        $this->override = $override;

        $this->modelGenerator->configure(
            $folder . '/' . static::TYPE_MODEL,
            $namespace . '\\' . static::TYPE_MODEL,
            $indent
        );

        $this->operationsGenerator->configure(
            $folder . '/' . static::TYPE_OPERATIONS,
            $namespace . '\\' . static::TYPE_OPERATIONS,
            $namespace . '\\' . static::TYPE_MODEL,
            $indent
        );

    }

    /**
     * {@inheritDoc}
     */
    public function generate(): bool
    {
        $content = file_get_contents($this->swaggerUri);
        if ($content === false) {
            return false;
        }
        if (empty($content)) {
            return false;
        }
        $json = json_decode($content, true);
        if ($json === null) {
            return false;
        }

        $this->processDefinitions($json);

        $this->processPaths($json);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function processDefinitions(array $json): bool
    {
        if (!isset($json['definitions'])) {
            return false;
        }

        $this->modelGenerator->setDefinitions($json['definitions']);
        $this->modelGenerator->setOverwrite($this->override);

        return $this->modelGenerator->generate();
    }

    /**
     * {@inheritDoc}
     */
    public function processPaths(array $json, bool $overwrite = false): bool
    {
        if (!isset($json['paths'])) {
            return false;
        }

        $this->operationsGenerator->setPaths($json['paths']);
        $this->operationsGenerator->setOverwrite($this->override);

        return $this->operationsGenerator->generate();
    }

    /**
     * {@inheritDoc}
     */
    public function getSwaggerUri(): string
    {
        return $this->swaggerUri;
    }

    /**
     * {@inheritDoc}
     */
    public function setSwaggerUri(string $swaggerUri): void
    {
        $this->swaggerUri = $swaggerUri;
    }

    /**
     * {@inheritDoc}
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * {@inheritDoc}
     */
    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function getIndent(): string
    {
        return $this->indent;
    }

    /**
     * {@inheritDoc}
     */
    public function setIndent(string $indent): void
    {
        $this->indent = $indent;
    }

    /**
     * {@inheritDoc}
     */
    public function getModelGenerator(): SwaggerModelGeneratorInterface
    {
        return $this->modelGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setModelGenerator(SwaggerModelGeneratorInterface $modelGenerator): void
    {
        $this->modelGenerator = $modelGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function getOperationsGenerator(): SwaggerOperationsGeneratorInterface
    {
        return $this->operationsGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function setOperationsGenerator(SwaggerOperationsGeneratorInterface $operationsGenerator): void
    {
        $this->operationsGenerator = $operationsGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function isOverride(): bool
    {
        return $this->override;
    }

    /**
     * {@inheritDoc}
     */
    public function setOverride(bool $override): void
    {
        $this->override = $override;
    }
}
