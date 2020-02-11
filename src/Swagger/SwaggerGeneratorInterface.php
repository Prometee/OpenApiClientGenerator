<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger;

use Prometee\SwaggerClientGenerator\Base\PhpGeneratorInterface;

interface SwaggerGeneratorInterface extends PhpGeneratorInterface
{
    public const TYPE_OPERATIONS = 'Operations';
    public const TYPE_MODEL = 'Model';

    /**
     * @param string $swaggerUri
     * @param string $folder
     * @param string $namespace
     * @param string $indent
     * @param bool $override
     */
    public function configure(
        string $swaggerUri,
        string $folder,
        string $namespace,
        string $indent = '    ',
        bool $override = false
    );

    /**
     * @param string $folder
     */
    public function setFolder(string $folder): void;

    /**
     * @return bool
     */
    public function generateClasses(): bool;

    /**
     * @param array $definitions
     */
    public function setDefinitions(array $definitions): void;

    /**
     * @param string $swaggerUri
     */
    public function setSwaggerUri(string $swaggerUri): void;

    /**
     * @param string $indent
     */
    public function setIndent(string $indent): void;

    /**
     * @param array $json
     *
     * @return bool
     */
    public function processPaths(array $json): bool;

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void;

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return array
     */
    public function getDefinitions(): array;

    /**
     * @return string
     */
    public function getFolder(): string;

    /**
     * @param array $json
     *
     * @return bool
     */
    public function processDefinitions(array $json): bool;

    /**
     * @return string
     */
    public function getIndent(): string;

    /**
     * @return string
     */
    public function getSwaggerUri(): string;

    /**
     * @param SwaggerModelGeneratorInterface $modelGenerator
     */
    public function setModelGenerator(SwaggerModelGeneratorInterface $modelGenerator): void;

    /**
     * @return SwaggerModelGeneratorInterface
     */
    public function getModelGenerator(): SwaggerModelGeneratorInterface;

    /**
     * @return SwaggerOperationsGeneratorInterface
     */
    public function getOperationsGenerator(): SwaggerOperationsGeneratorInterface;

    /**
     * @param SwaggerOperationsGeneratorInterface $operationsGenerator
     */
    public function setOperationsGenerator(SwaggerOperationsGeneratorInterface $operationsGenerator): void;

    /**
     * @return bool
     */
    public function isOverride(): bool;

    /**
     * @param bool $override
     */
    public function setOverride(bool $override): void;
}