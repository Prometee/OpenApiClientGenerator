<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Helper;

use Exception;

class SwaggerOperationsHelper extends AbstractHelper implements SwaggerOperationsHelperInterface
{
    /**
     * {@inheritdoc}
     */
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
        $classPath = preg_replace('#{[^{]+}#i', '/', $classPath);

        return $classPath;
    }

    /**
     * {@inheritdoc}
     */
    public static function getOperationMethodName(string $path, string $operation, array $operationConfiguration): string
    {
        if (isset($operationConfiguration['operationId'])) {
            $operationName = $operationConfiguration['operationId'];
        } else {
            $hasPathParameters = preg_match('#{[^{]+}#', $path);
            $operationName = strtolower($operation) . ($hasPathParameters ? 'Item' : 'Collection');
        }

        $operationName = self::camelize($operationName);
        $operationName = lcfirst($operationName);
        return $operationName;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public static function getReturnType(array $responseConfiguration): ?string
    {
        foreach ([200, 201, 203, 204] as $httpCode) {
            if (!isset($responseConfiguration[$httpCode])) {
                continue;
            }
            if (!isset($responseConfiguration[$httpCode]['schema'])) {
                continue;
            }

            return static::getPhpTypeFromSwaggerConfiguration($responseConfiguration[$httpCode]['schema']);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function getPhpTypeFromSwaggerDefinitionName(string $ref): string
    {
        return $ref;
    }
}
