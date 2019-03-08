[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

## Swagger PHP7 client builder

This library is designed to generate PHP7 model and operation classes from a swagger file.

## Installation

Install using Composer :

```
$ composer require prometee/swagger-client-builder
```

## Usage

```php
$loader = require_once( __DIR__.'/vendor/autoload.php');

use Prometee\SwaggerClientBuilder\SwaggerGenerator;

$baseUri = 'https://github.com/OAI/OpenAPI-Specification/raw/master/examples/v2.0/json/petstore-expanded.json';
$folder = __DIR__ . '/Build';
$namespace = 'Tests\\Prometee\\SwaggerClientBuilder\\Build';
$overwrite = true;
/*
$abstractOperationClass = \MyVendor\MyApi\AbstractOperations::class;
$throwClasses = [
    '\\MyVendor\MyApi\\ApiException'=>'ApiException',
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

```

[ico-version]: https://img.shields.io/packagist/v/Prometee/swagger-client-builder.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Prometee/SwaggerClientBuilder/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Prometee/SwaggerClientBuilder.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/prometee/swagger-client-builder
[link-travis]: https://travis-ci.org/Prometee/SwaggerClientBuilder
[link-scrutinizer]: https://scrutinizer-ci.com/g/Prometee/SwaggerClientBuilder/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Prometee/SwaggerClientBuilder
