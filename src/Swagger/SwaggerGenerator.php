<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger;

class SwaggerGenerator
{
    public const TYPE_MODEL = 'Model';
    const TYPE_OPERATIONS = 'Operations';

    /** @var string */
    protected $swaggerUri;
    /** @var string */
    protected $folder;
    /** @var string */
    protected $namespace;

    /** @var array */
    protected $definitions;

    /** @var string */
    protected $indent;

    /** @var SwaggerModelGenerator */
    protected $modelGenerator;
    /** @var SwaggerOperationsGenerator */
    protected $operationsGenerator;

    /**
     * @param string $swaggerUri
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     */
    public function __construct(string $swaggerUri, string $folder, string $namespace, string $indent = '    ')
    {
        $this->swaggerUri = $swaggerUri;
        $this->folder = $folder;
        $this->namespace = $namespace;
        $this->indent = $indent;
        $this->modelGenerator = new SwaggerModelGenerator(
            $folder . '/' . static::TYPE_MODEL,
            $namespace . '\\' . static::TYPE_MODEL,
            $indent
        );
        $this->operationsGenerator = new SwaggerOperationsGenerator(
            $folder . '/' . static::TYPE_OPERATIONS,
            $namespace . '\\' . static::TYPE_OPERATIONS,
            $namespace . '\\' . static::TYPE_MODEL,
            $indent
        );
    }

    /**
     * @param bool $overwrite
     *
     * @return bool
     */
    public function generate(bool $overwrite = false): bool
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
        $this->processDefinitions($json, $overwrite);

        $this->processPaths($json, $overwrite);

        return true;
    }

    /**
     * @param array $json
     * @param bool $overwrite
     *
     * @return bool
     */
    public function processDefinitions(array $json, bool $overwrite = false): bool
    {
        if (!isset($json['definitions'])) {
            return false;
        }
        $this->modelGenerator->setDefinitions($json['definitions']);

        return $this->modelGenerator->generate($overwrite);
    }

    public function processPaths(array $json, bool $overwrite = false): bool
    {
        if (!isset($json['paths'])) {
            return false;
        }
        $this->operationsGenerator->setPaths($json['paths']);

        return $this->operationsGenerator->generate($overwrite);
    }

    /**
     * @return string
     */
    public function getSwaggerUri(): string
    {
        return $this->swaggerUri;
    }

    /**
     * @param string $swaggerUri
     */
    public function setSwaggerUri(string $swaggerUri): void
    {
        $this->swaggerUri = $swaggerUri;
    }

    /**
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * @param string $folder
     */
    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array $definitions
     */
    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
    }

    /**
     * @return string
     */
    public function getIndent(): string
    {
        return $this->indent;
    }

    /**
     * @param string $indent
     */
    public function setIndent(string $indent): void
    {
        $this->indent = $indent;
    }

    /**
     * @return SwaggerModelGenerator
     */
    public function getModelGenerator(): SwaggerModelGenerator
    {
        return $this->modelGenerator;
    }

    /**
     * @return SwaggerOperationsGenerator
     */
    public function getOperationsGenerator(): SwaggerOperationsGenerator
    {
        return $this->operationsGenerator;
    }
}
