<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Converter;

use JsonException;

class OpenApiConverter implements OpenApiConverterInterface
{
    public function __construct(
        protected string $swaggerUri,
        protected ModelConverterInterface $modelConverter,
        protected OperationsConverterInterface $operationsConverter,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function convert(): ?array
    {
        $content = file_get_contents($this->swaggerUri);

        if ($content === false) {
            return null;
        }

        if (empty($content)) {
            return null;
        }

        /** @var array|null $json */
        $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if ($json === null) {
            return null;
        }

        return [
            ...$this->modelConverter->convert($json['definitions'] ?? $json['components']['schemas'] ?? []),
            ...$this->operationsConverter->convert($json['paths'] ?? []),
        ];
    }
}
