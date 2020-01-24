<?php

declare(strict_types=1);

namespace Tests\Prometee\SwaggerClientBuilder;

use PHPUnit\Framework\TestCase;
use Prometee\SwaggerClientBuilder\Swagger\Builder\SwaggerGeneratorBuilder;

class GeneratorTest extends TestCase
{
    /** @test */
    public function generate()
    {
        $baseUri = __DIR__ . '/Resources/swagger.json';
        $folder = __DIR__ . '/../etc/build';
        $namespace = 'Tests\\Prometee\\SwaggerClientBuilder\\PhpBuilder\\Classes\\Build';
        $indent = '    ';
        $overwrite = true;

        $swaggerGeneratorBuilder = new SwaggerGeneratorBuilder();
        $swaggerGenerator = $swaggerGeneratorBuilder->build();
        $swaggerGenerator->configure($baseUri, $folder, $namespace, $indent, $overwrite);

        /*
        $abstractOperationClass = \MyVendor\MyApi\AbstractOperations::class;
        $throwClasses = [
            '\\MyVendor\MyApi\\ApiException'=>'ApiException',
            '\\Http\\Client\\Exception'=>'HttpClientException',
            '\\Symfony\\Component\\Serializer\\Exception\\ExceptionInterface'=>'SerializerExceptionInterface',
        ];
        $operationsGenerator = $swaggerGenerator->getOperationsGenerator();
        $operationsGenerator->setAbstractOperationClass($abstractOperationClass);
        $operationsGenerator->setThrowsClasses($throwClasses);
        */

        $result = $swaggerGenerator->generate();

        $this->assertTrue($result);
    }
}
