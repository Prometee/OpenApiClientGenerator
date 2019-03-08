<?php

declare(strict_types=1);

namespace Tests\Prometee\SwaggerClientBuilder;

use PHPUnit\Framework\TestCase;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerGenerator;

class GeneratorTest extends TestCase
{
    /** @test */
    public function generate()
    {
        $baseUri = 'https://github.com/OAI/OpenAPI-Specification/raw/master/examples/v2.0/json/petstore-expanded.json';
        $folder = __DIR__ . '/Build';
        $namespace = 'Tests\\Prometee\\SwaggerClientBuilder\\Build';
        $overwrite = true;
        /*
        $abstractOperationClass = \Flux\Quickbooks\Api\AbstractOperations::class;
        $throwClasses = [
            '\\Flux\\Quickbooks\\Api\\ApiException'=>'ApiException',
            '\\Http\\Client\\Exception'=>'HttpClientException',
            '\\Symfony\\Component\\Serializer\\Exception\\ExceptionInterface'=>'SerializerExceptionInterface',
        ];
        */

        $swaggerGenerator = new SwaggerGenerator($baseUri, $folder, $namespace);
        /*
        $operationsGenerator = $swaggerGenerator->getOperationsGenerator();
        $operationsGenerator->setAbstractOperationClass($abstractOperationClass);
        $operationsGenerator->setThrowsClasses($throwClasses);
        */
        $result = $swaggerGenerator->generate($overwrite);

        $this->assertTrue($result);
    }
}
