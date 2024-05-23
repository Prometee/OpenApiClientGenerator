<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\PhpGenerator\Converter;

use Prometee\PhpClassGenerator\Model\PhpDoc\PhpDocInterface;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\OperationsHelperInterface;
use Prometee\SwaggerClientGenerator\PhpGenerator\Operation\OperationsMethodGeneratorInterface;

class OperationsConverter implements OperationsConverterInterface
{
    protected array $paths = [];
    /** @var string[] */
    protected array $throwsClasses = [];
    protected ?string $abstractOperationsClass = null;

    protected array $generatedConfig = [];

    public function __construct(
        protected string                             $operationsNamespacePrefix,
        protected string                             $modelNamespace,
        protected OperationsHelperInterface          $helper,
        protected OperationsMethodGeneratorInterface $operationsMethodGenerator,
    ) {
    }

    public function convert(array $paths): array
    {
        $this->setPaths($paths);

        $this->generatedConfig = [];
        foreach ($this->paths as $path => $pathConfig) {
            $config = $this->generateClass($path, $pathConfig);

            if (isset($this->generatedConfig[$config['class']])) {
                $config['methods'] = [
                    ...$this->generatedConfig[$config['class']]['methods'],
                    ...$config['methods'],
                ];
            }

            $this->generatedConfig[$config['class']] = $config;
        }

        return $this->generatedConfig;
    }

    public function processPaths(array $json): bool
    {
        if (!isset($json['paths'])) {
            return false;
        }
        foreach ($this->paths as $path => $operationConfigurations) {
            $this->generateClass($path, $operationConfigurations);
        }

        return true;
    }

    public function generateClass(string $path, array $pathConfig): array
    {
        // Class
        $config = $this->convertClass($path);

        // Methods
        $config['methods'] = $this->processOperations($path, $pathConfig);

        return $config;
    }

    protected function convertClass(string $path): array
    {
        [$namespace, $className] = $this->getClassNameAndNamespaceFromPath(
            $path,
            '',
            static::CLASS_SUFFIX
        );

        return [
            'class' => $namespace . '\\' . $className,
            'extends' => $this->abstractOperationsClass,
        ];
    }

    public function processOperations(string $path, array $pathConfig): array
    {
        $methods = [];
        foreach ($pathConfig as $operation => $operationConfig) {
            if (!is_array($operationConfig)) {
                continue;
            }
            $methods[] = $this->processOperation($path, $operation, $operationConfig);
        }

        return $methods;
    }

    public function processOperation(
        string $path,
        string $operation,
        array $operationConfiguration
    ): array {
        $operation = strtolower($operation);

        $operationMethodName = $this->helper::getOperationMethodName($path, $operation, $operationConfiguration);
        $returnType = $this->processOperationReturnType($operationConfiguration);

        $parametersConfiguration = $operationConfiguration['parameters'] ?? [];
        $operationParameters = $this->processOperationParameters($parametersConfiguration);

        $requestBodyConfiguration = $operationConfiguration['requestBody'] ?? [];
        if ([] !== $requestBodyConfiguration) {
            $operationParameters[] = $this->processOperationRequestBody($requestBodyConfiguration);
        }

        $phpdoc = [
            PhpDocInterface::TYPE_DESCRIPTION => [
                sprintf('%s %s', strtoupper($operation), $path),
                '',
                $operationConfiguration['description'] ?? null,
            ],
        ];

        $phpdoc[PhpDocInterface::TYPE_THROWS] = [];
        foreach ($this->throwsClasses as $throwsClass => $className) {
            $phpdoc[PhpDocInterface::TYPE_THROWS][] = $throwsClass;
        }

        $bodyLines = $this->operationsMethodGenerator->createMethodBodyFromSwaggerConfiguration(
            $path,
            $operation,
            $parametersConfiguration,
            $returnType
        );

        return [
            'phpdoc' => $phpdoc,
            'scope' => 'public',
            'name' => $operationMethodName,
            'return_types' => [$returnType],
            'parameters' => $operationParameters,
            'body' => $bodyLines,
            'static' => false,
        ];
    }

    public function processOperationReturnType(array $operationConfiguration): string
    {
        $returnTypes = [];
        if (isset($operationConfiguration['responses'])) {
            $returnTypes = $this->helper::getReturnTypes($operationConfiguration['responses']);
        }

        if ([] === $returnTypes) {
            return 'void';
        }

        $phpReturnTypes = [];
        foreach ($returnTypes as $returnType) {
            $phpReturnTypes[] = $this->getPhpNameFromType($returnType);
        }

        return implode('|', $phpReturnTypes);
    }

    public function processOperationParameters(array $operationParameters): array
    {
        $methodParameters = [];
        foreach ($operationParameters as $parameterConfiguration) {
            $methodParameters[] = $this->createAnOperationParameter($parameterConfiguration);
        }

        return $methodParameters;
    }

    public function processOperationRequestBody(array $operationRequestBody): array
    {
        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($operationRequestBody['content']['application/json'] ?? []);
        if ($type !== null) {
            $type = $this->getPhpNameFromType($type);
        }
        $types = [$type];

        return [
            'name' => 'body',
            'types' => $types,
            'description' => $operationRequestBody['description'] ?? '',
        ];
    }

    public function createAnOperationParameter(array $parameterConfiguration): array
    {
        $name = $parameterConfiguration['name'] ?? '';

        $type = $this->helper::getPhpTypeFromSwaggerConfiguration($parameterConfiguration);
        if ($type !== null) {
            $type = $this->getPhpNameFromType($type);
        }
        $types = [$type];

        $name = lcfirst($this->helper::cleanStr($name));

        $value = $this->buildValueForOperationParameter($parameterConfiguration, $type);
        if ($value === 'null') {
            $types[] = $value;
        }

        $description = $parameterConfiguration['description'] ?? '';

        return [
            'name' => $name,
            'types' => $types,
            'value' => $value,
            'description' => $description,
        ];
    }

    public function buildValueForOperationParameter(array $parameterConfiguration, ?string $type): ?string
    {
        $value = null;
        if (isset($parameterConfiguration['default'])) {
            $value = (string) $parameterConfiguration['default'];
            if ($type === 'string') {
                $value = "'" . addslashes($value) . "'";
            }
        }

        if (isset($parameterConfiguration['required'])) {
            if ((bool)$parameterConfiguration['required']) {
                $value = null;
            } else {
                $value = $value ?? 'null';
            }
        }

        return $value;
    }

    public function getClassNameAndNamespaceFromPath(string $path, string $classPrefix = '', string $classSuffix = ''): array
    {
        $classPath = $this->helper::getClassPathFromPath($path);
        $classParts = explode('/', $classPath);
        $className = array_pop($classParts);
        $namespace = implode('\\', $classParts);
        $namespace = ($namespace === '' ? '' : '\\' . $namespace);
        $namespace = $this->operationsNamespacePrefix . $namespace;

        return [
            $namespace,
            $classPrefix . $className . $classSuffix,
        ];
    }

    public function getPhpNameFromType(string $type): string
    {
        $class = $type;
        if (
            str_starts_with($type, '#/definitions/') ||
            str_starts_with($type, '#/components/schemas/')
        ) {
            /** @var string $className */
            $className = preg_replace([
                '$#/definitions/$',
                '$#/components/schemas/$',
            ], '', $type);
            $className = $this->helper::camelize($className);
            $class = '\\' . $this->modelNamespace . '\\' . $className;
        }

        if ($class !== $type && preg_match('#\[]$#', $type)) {
            $class .= "[]";
        }

        return $class;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    public function getThrowsClasses(): array
    {
        return $this->throwsClasses;
    }

    public function setThrowsClasses(array $throwsClasses): void
    {
        $this->throwsClasses = $throwsClasses;
    }

    public function getAbstractOperationsClass(): ?string
    {
        return $this->abstractOperationsClass;
    }

    public function setAbstractOperationsClass(?string $abstractOperationsClass): void
    {
        $this->abstractOperationsClass = $abstractOperationsClass;
    }
}
