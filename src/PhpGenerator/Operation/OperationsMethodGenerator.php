<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Operation;

use Prometee\SwaggerClientGenerator\OpenApi\Helper\OperationsHelper;

class OperationsMethodGenerator implements OperationsMethodGeneratorInterface
{
    public function createMethodBodyFromSwaggerConfiguration(
        string $path,
        string $operation,
        array $parametersConfiguration,
        string $returnType,
        ?string $indent = "\t"
    ): array {
        $bodyParam = null;

        if (in_array($operation, ['post', 'put', 'patch'])) {
            $bodyParam = $this->buildBodyParam();
        }

        return $this->createOperationTypeLines(
            $path,
            $operation,
            $parametersConfiguration,
            $returnType,
            $bodyParam
        );
    }

    public function createOperationTypeLines(
        string $path,
        string $operation,
        array $parametersConfiguration,
        string $returnType,
        ?string $bodyParam,
        ?string $indent = "\t"
    ): array {
        $format = $indent . $indent . '\'%1$s\' => $%1$s,';
        $pathParams = $this->buildParamsType('path', $parametersConfiguration, $format);
        $queryParams = $this->buildParamsType('query', $parametersConfiguration, $format);

        $pathParams = 0 === count($pathParams) ? [$indent . '[],'] : [$indent . '[', ...$pathParams, $indent . '],'];
        $queryParams = 0 === count($queryParams) ? [$indent . '[],'] : [$indent . '[', ...$queryParams, $indent . '],'];

        $arguments = [
            sprintf('%s\'%s\',', $indent, $path),
            sprintf('%s%s,', $indent, $bodyParam),
            sprintf('%s%s,', $indent, $this->getMinifiedReturnType($returnType)),
            ...$pathParams,
            ...$queryParams,
        ];

        if (null === $bodyParam) {
            unset($arguments[1]);
        }

        return [
            ($returnType === 'void' ? '' : 'return ') . '$this->exec' . ucfirst($operation) . 'Operation(',
                ...$arguments,
            ');',
        ];
    }

    public function buildParamsType(string $type, array $parametersConfiguration, string $format): array
    {
        $params = [];
        foreach ($parametersConfiguration as $operationParameter) {
            if (!isset($operationParameter['in'])) {
                continue;
            }
            if ($type !== $operationParameter['in']) {
                continue;
            }
            if (!isset($operationParameter['name'])) {
                continue;
            }
            $parameterName = lcfirst(OperationsHelper::cleanStr($operationParameter['name']));
            $param = sprintf($format, $parameterName);
            $params[] = $param;
        }

        return $params;
    }

    public function buildBodyParam(): string
    {
        return '$body';
    }

    /**
     * @param string[] $types
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
            if (preg_match('#\[]$#', $type)) {
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

    public function getMinifiedReturnType(string $returnType): string
    {
        if (empty($returnType)) {
            return 'null';
        }

        if ($returnType === 'void') {
            return 'null';
        }

        if ($returnType === 'array') {
            return '\'array\'';
        }

        $suffix = '';
        if (self::getPhpType([$returnType]) === 'array') {
            $suffix = '.\'[]\'';
        }

        return rtrim($returnType, '[]') . '::class' . $suffix;
    }
}
