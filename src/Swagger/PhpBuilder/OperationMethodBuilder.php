<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\PhpBuilder;

use Prometee\SwaggerClientBuilder\PhpBuilder\Classes\Method\MethodBuilder;
use Prometee\SwaggerClientBuilder\Swagger\Helper\SwaggerOperationsHelper;

class OperationMethodBuilder extends MethodBuilder implements OperationMethodBuilderInterface
{
    /** @var {@inheritDoc} */
    protected $scope = self::SCOPE_PUBLIC;

    /**
     * {@inheritDoc}
     */
    public function configure(
        string $scope,
        string $name,
        array $returnTypes = [],
        bool $static = false,
        string $description = ''
    )
    {
        parent::configure(self::SCOPE_PUBLIC, $name, $returnTypes, false, $description);
    }

    public function getMinifiedReturnType(): string
    {
        if (empty($this->returnTypes)) {
            return 'null';
        }

        if ($this->returnTypes === ['void']) {
            return 'null';
        }

        if ($this->returnTypes === ['array']) {
            return '\'array\'';
        }

        $suffix = '';
        if ($this->getPhpReturnType() === 'array') {
            $suffix = '.\'[]\'';
        }

        return rtrim($this->returnTypes[0], '[]') . '::class' . $suffix;
    }

    /**
     * @param string $path
     * @param string $operation
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addMethodBodyFromSwaggerConfiguration(string $path, string $operation, array $operationParameters, string $indent = null): void
    {
        switch ($operation) {
            case 'get':
                $this->addGetOperationLines($path, $operationParameters, $indent);

                break;
            case 'post':
                $this->addPostOperationLines($path, $operationParameters, $indent);

                break;
            case 'put':
                $this->addPutOperationLines($path, $operationParameters, $indent);

                break;
            case 'delete':
                $this->addDeleteOperationLines($path, $operationParameters, $indent);

                break;
        }
    }

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addGetOperationLines(string $path, array $operationParameters, string $indent = null): void
    {
        [$pathParams, $queryParams] = $this->buildPathAndQueryParams($operationParameters);

        $format  = $this->returnTypes === ['void'] ? '' : 'return ';
        $format .=
            '$this->execGetOperation(' . "\n"
                . '%1$s\'%2$s\',' . "\n"
                . '%1$s%3$s,' . "\n"
                . '%1$s[' . $pathParams . '],' . "\n"
                . '%1$s[' . $queryParams . ']' . "\n"
            . ');' . "\n"
        ;

        $this->addLine(sprintf(
            $format,
            $indent,
            $path,
            $this->getMinifiedReturnType(),
            $pathParams,
            $queryParams
        ));
    }

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addPostOperationLines(string $path, array $operationParameters, string $indent = null): void
    {
        [$pathParams, $queryParams] = $this->buildPathAndQueryParams($operationParameters);

        $format  = $this->returnTypes === ['void'] ? '' : 'return ';
        $format .=
            '$this->execPostOperation(' . "\n"
            . '%1$s\'%2$s\',' . "\n"
            . '%1$s%3$s,' . "\n"
            . '%1$s%4$s,' . "\n"
            . '%1$s[' . $pathParams . '],' . "\n"
            . '%1$s[' . $queryParams . ']' . "\n"
            . ');' . "\n"
        ;

        $bodyParam = $this->buildQueryParam($operationParameters);

        $this->addLine(sprintf(
            $format,
            $indent,
            $path,
            $bodyParam,
            $this->getMinifiedReturnType()
        ));
    }

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addPutOperationLines(string $path, array $operationParameters, string $indent = null): void
    {
        [$pathParams, $queryParams] = $this->buildPathAndQueryParams($operationParameters);

        $format  = $this->returnTypes === ['void'] ? '' : 'return ';
        $format .=
            '$this->execPutOperation(' . "\n"
            . '%1$s\'%2$s\',' . "\n"
            . '%1$s%3$s,' . "\n"
            . '%1$s%4$s,' . "\n"
            . '%1$s[' . $pathParams . '],' . "\n"
            . '%1$s[' . $queryParams . ']' . "\n"
            . ');' . "\n"
        ;

        $bodyParam = $this->buildQueryParam($operationParameters);

        $this->addLine(sprintf(
            $format,
            $indent,
            $path,
            $bodyParam,
            $this->getMinifiedReturnType()
        ));
    }

    /**
     * @param string $path
     * @param array $operationParameters
     * @param string|null $indent
     */
    public function addDeleteOperationLines(string $path, array $operationParameters, string $indent = null): void
    {
        [$pathParams, $queryParams] = $this->buildPathAndQueryParams($operationParameters);

        $format  = $this->returnTypes === ['void'] ? '' : 'return ';
        $format .=
            '$this->execDeleteOperation(' . "\n"
            . '%1$s\'%2$s\',' . "\n"
            . '%1$s%3$s,' . "\n"
            . '%1$s[' . $pathParams . '],' . "\n"
            . '%1$s[' . $queryParams . ']' . "\n"
            . ');' . "\n"
        ;

        $this->addLine(sprintf(
            $format,
            $indent,
            $path,
            $this->getMinifiedReturnType()
        ));
    }

    /**
     * @param array $operationParameters
     *
     * @return array
     */
    protected function buildPathAndQueryParams(array $operationParameters): array
    {
        $pathParams = '';
        $queryParams = '';
        foreach ($operationParameters as $operationParameter) {
            if (!isset($operationParameter['in'])) {
                continue;
            }
            if (!isset($operationParameter['name'])) {
                continue;
            }
            $parameterName = lcfirst(SwaggerOperationsHelper::cleanStr($operationParameter['name']));
            switch ($operationParameter['in']) {
                case 'query':
                    $queryParams .= "\n" . '%1$s%1$s\'' . $parameterName . '\' => $' . $parameterName . ',';

                    break;
                case 'path':
                    $pathParams .= "\n" . '%1$s%1$s\'' . $parameterName . '\' => $' . $parameterName . ',';

                    break;
            }
        }
        if (!empty($pathParams)) {
            $pathParams .= "\n" . '%1$s';
        }
        if (!empty($queryParams)) {
            $queryParams .= "\n" . '%1$s';
        }

        return [$pathParams, $queryParams];
    }

    /**
     * @param array $operationParameters
     *
     * @return string
     */
    protected function buildQueryParam(array $operationParameters): string
    {
        $bodyParam = 'null';
        foreach ($operationParameters as $operationParameter) {
            if (!isset($operationParameter['in'])) {
                continue;
            }
            if ($operationParameter['in'] !== 'body') {
                continue;
            }
            if (!isset($operationParameter['name'])) {
                continue;
            }
            $parameterName = lcfirst(SwaggerOperationsHelper::cleanStr($operationParameter['name']));
            $bodyParam = '$' . $parameterName;
        }

        return $bodyParam;
    }
}
