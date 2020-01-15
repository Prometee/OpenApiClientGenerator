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
        $baseUri = 'https://github.com/OAI/OpenAPI-Specification/raw/master/examples/v2.0/json/petstore-expanded.json';
        $folder = __DIR__ . '/../etc/build';
        $namespace = 'Tests\\Prometee\\SwaggerClientBuilder\\PhpBuilder\\Classes\\Build';
        $overwrite = true;

        $swaggerGeneratorBuilder = new SwaggerGeneratorBuilder();
        $swaggerGenerator = $swaggerGeneratorBuilder->build();
        $swaggerGenerator->configure($baseUri, $folder, $namespace);

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

        $result = $swaggerGenerator->generate($overwrite);

        $this->assertTrue($result);
    }
}
