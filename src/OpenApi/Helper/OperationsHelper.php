<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\OpenApi\Helper;

use Exception;

class OperationsHelper extends AbstractHelper implements OperationsHelperInterface
{
    public static function getClassPathFromPath(string $path): string
    {
        $classPath = trim($path, '/');
        $words = explode('/', $classPath);
        $words = array_map('ucfirst', $words);
        $classPath = implode('', $words);

        $words = explode('_', $classPath);
        $words = array_map('ucfirst', $words);
        $classPath = implode('', $words);

        // replace all {path_parameter} by /
        /** @var string $classPath */
        $classPath = preg_replace('#{[^{]+}#', '/', $classPath);

        return trim($classPath, '/');
    }

    public static function getOperationMethodName(
        string $path,
        string $operation,
        array $operationConfiguration
    ): string {
        if (isset($operationConfiguration['operationId'])) {
            $operationName = $operationConfiguration['operationId'];
        } else {
            $hasPathParameters = preg_match('#{[^{]+}#', $path);
            $operationName = strtolower($operation) . ($hasPathParameters ? 'Item' : 'Collection');
        }

        $operationName = static::camelize($operationName);
        $operationName = lcfirst($operationName);
        return $operationName;
    }

    /**
     * @throws Exception
     */
    public static function getReturnTypes(
        array $responseConfiguration,
        bool $onlyOkCodes = true,
    ): array {
        $types = [];
        foreach ($responseConfiguration as $httpCode => $responseCodeConfig) {
            $okCodes = [200, 201, 203, 204];
            if ($onlyOkCodes !== in_array($httpCode, $okCodes, true)) {
                continue;
            }

            $schema = $responseCodeConfig['schema'] ?? null;
            if (null === $schema) {
                $schema = $responseCodeConfig['content']['application/json']['schema'] ?? null;
            }

            if (null === $schema) {
                continue;
            }

            $types[] = static::getPhpTypeFromSwaggerConfiguration($schema);
        }

        return $types;
    }

    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string
    {
        return $ref;
    }
}
