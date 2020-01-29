[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

## Swagger PHP7 client generator

This library is designed to generate PHP7 model and operation classes from a swagger file.

## Installation

Install using Composer :

```
$ composer require prometee/swagger-client-generator
```

## Usage

```php

$loader = require_once( __DIR__.'/vendor/autoload.php');

use Prometee\SwaggerClientGenerator\Swagger\Builder\SwaggerGeneratorBuilder;
use Prometee\SwaggerClientGenerator\Swagger\SwaggerGeneratorInterface;

$baseUri = 'https://github.com/OAI/OpenAPI-Specification/raw/master/examples/v2.0/json/petstore-expanded.json';
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

```

[ico-version]: https://img.shields.io/packagist/v/Prometee/swagger-client-generator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Prometee/SwaggerClientGenerator/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Prometee/SwaggerClientGenerator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/prometee/swagger-client-generator
[link-travis]: https://travis-ci.org/Prometee/SwaggerClientGenerator
[link-scrutinizer]: https://scrutinizer-ci.com/g/Prometee/SwaggerClientGenerator/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Prometee/SwaggerClientGenerator
