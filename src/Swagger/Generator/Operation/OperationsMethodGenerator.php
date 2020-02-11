<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Swagger\Generator\Operation;

use Prometee\SwaggerClientGenerator\Base\Generator\Method\MethodGenerator;
use Prometee\SwaggerClientGenerator\Swagger\Helper\SwaggerOperationsHelper;

class OperationsMethodGenerator extends MethodGenerator implements OperationsMethodGeneratorInterface
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

    /**
     * {@inheritDoc}
     */
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
     * {@inheritDoc}
     */
    public function addMethodBodyFromSwaggerConfiguration(string $path, string $operation, array $operationParameters, string $indent = null): void
    {
        $bodyParam = null;

        if (in_array($operation, ['post', 'put', 'patch'])) {
            $bodyParam = $this->buildBodyParam($operationParameters);
        }

        $this->addOperationTypeLines($operation, $path, $operationParameters, $bodyParam, $indent);
    }

    /**
     * {@inheritDoc}
     */
    public function addOperationTypeLines(string $operation, string $path, array $operationParameters, string $bodyParam = null, string $indent = null): void
    {
        $pathParamsFormat = $this->generateParamsType('path', $operationParameters, '%1$s', '%2$s%2$s');
        $queryParamsFormat = $this->generateParamsType('query', $operationParameters, '%1$s', '%2$s%2$s');

        $format =
            '%3$s$this->exec%4$sOperation(%1$s'
                . '%2$s\'%5$s\',%1$s'
                . (null === $bodyParam ? '' : '%2$s'.$bodyParam.',%1$s')
                . '%2$s%6$s,%1$s'
                . '%2$s['.$pathParamsFormat.(empty($pathParamsFormat) ? '' : '%1$s%2$s').'],%1$s'
                . '%2$s['.$queryParamsFormat.(empty($queryParamsFormat) ? '' : '%1$s%2$s').']%1$s'
            . ');%1$s'
        ;

        $this->addLine(sprintf($format,
            "\n",
            $indent,
            $this->returnTypes === ['void'] ? '' : 'return ',
            ucfirst($operation),
            $path,
            $this->getMinifiedReturnType()
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function generateParamsType(string $type, array $operationParameters, string $lineBreak = null, string $indent = null): string
    {
        $params = $this->buildParamsType($type, $operationParameters, '\'%1$s\' => $%1$s');
        if (count($params) === 0) {
            return '';
        }

        $glue = sprintf(',%s%s', $lineBreak, $indent);
        return sprintf('%s%s%s',
            $lineBreak,
            $indent,
            implode($glue, $params)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildParamsType(string $type, array $operationParameters, string $format): array
    {
        $pathParams = [];
        foreach ($operationParameters as $operationParameter) {
            if (!isset($operationParameter['in'])) {
                continue;
            }
            if ($type !== $operationParameter['in']) {
                continue;
            }
            if (!isset($operationParameter['name'])) {
                continue;
            }
            $parameterName = lcfirst(SwaggerOperationsHelper::cleanStr($operationParameter['name']));
            $param = sprintf($format, $parameterName);
            $pathParams[] = $param;
        }

        return $pathParams;
    }

    /**
     * {@inheritDoc}
     */
    public function buildBodyParam(array $operationParameters): string
    {
        $bodyParams = $this->buildParamsType('body', $operationParameters, '$%s');

        // only one should exists
        $bodyParam = implode('', $bodyParams);

        if (empty($bodyParams)) {
            return 'null';
        }

        return $bodyParam;
    }
}
