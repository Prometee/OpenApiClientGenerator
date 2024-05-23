<?php

declare(strict_types=1);

namespace Tests\Prometee\SwaggerClientGenerator;

use PHPUnit\Framework\TestCase;
use Prometee\PhpClassGenerator\Builder\ClassBuilder;
use Prometee\PhpClassGenerator\Builder\Model\ModelFactoryBuilder;
use Prometee\PhpClassGenerator\Builder\View\ViewFactoryBuilder;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\ModelHelper;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\OperationsHelper;
use Prometee\SwaggerClientGenerator\Operations\AbstractOperations;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\ModelConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\OpenApiConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\OperationsConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\Operation\OperationsMethodGenerator;
use Prometee\SwaggerClientGenerator\PhpGenerator\PhpGenerator;

class GeneratorTest extends TestCase
{
    public function testGenerateClasses(): void
    {
        $baseUri = __DIR__ . '/Resources/swagger.json';
        $path = __DIR__ . '/../etc/build';
        $namespace = 'Tests\\Prometee\\SwaggerClientGenerator\\Build';
        $abstractOperationsClass = AbstractOperations::class;
        $throwsClasses = [];

        $openApiPhpGenerator = new PhpGenerator(
            new ClassBuilder(
                new ModelFactoryBuilder(),
                new ViewFactoryBuilder()
            )
        );

        $modelConverter = new ModelConverter(
            'Model',
            $namespace,
            new ModelHelper()
        );

        $operationsConverter = new OperationsConverter(
            'Operations',
            $namespace . "\\" . $modelConverter->getModelNamespacePrefix(),
            new OperationsHelper(),
            new OperationsMethodGenerator()
        );

        $openApiPhpGenerator->configure($path, $namespace);
        $operationsConverter->setAbstractOperationsClass($abstractOperationsClass);
        $operationsConverter->setThrowsClasses($throwsClasses);

        $openApiConverter = new OpenApiConverter($baseUri, $modelConverter, $operationsConverter);
        $classConfig = $openApiConverter->convert();

        $this->assertNotNull($classConfig);

        $openApiPhpGenerator->setClassesConfig($classConfig);

        $openApiPhpGenerator->generate();
    }
}
