<?php

declare(strict_types=1);

namespace Tests\Prometee\SwaggerClientGenerator;

use PHPUnit\Framework\TestCase;
use Prometee\SwaggerClientGenerator\Swagger\Builder\SwaggerGeneratorBuilder;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerGeneratorInterface;

class GeneratorTest extends TestCase
{
    /** @test */
    public function generate()
    {
        // $baseUri = __DIR__ . '/Resources/swagger.json';
        $baseUri = 'https://developer.sage.com/api/accounting/files/swagger.full.json';
        $folder = __DIR__ . '/../etc/build';
        $namespace = 'Tests\\Prometee\\SwaggerClientGenerator\\Build';
        $indent = '    ';
        $overwrite = true;

        $swaggerGeneratorBuilder = new SwaggerGeneratorBuilder();
        /** @var SwaggerGeneratorInterface $swaggerGenerator */
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

        $result = $swaggerGenerator->generateClasses();

        $this->assertTrue($result);
    }
}
