[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Quality Score][ico-code-quality]][link-code-quality]

## OpenApi PHP8 client generator

This library is designed to generate PHP8 model and operation classes from an Open API json file.

## Installation

Install using Composer :

```
$ composer require prometee/openapi-client-generator
```

## Usage

```php

declare(strict_types=1);

$loader = require_once( __DIR__.'/vendor/autoload.php');

use Prometee\PhpClassGenerator\Builder\ClassBuilder;
use Prometee\PhpClassGenerator\Builder\Model\ModelFactoryBuilder;
use Prometee\PhpClassGenerator\Builder\View\ViewFactoryBuilder;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\ModelHelper;
use Prometee\SwaggerClientGenerator\OpenApi\Helper\OperationsHelper;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\ModelConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\OpenApiConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\Converter\OperationsConverter;
use Prometee\SwaggerClientGenerator\PhpGenerator\PhpGenerator;
use Prometee\SwaggerClientGenerator\PhpGenerator\Operation\OperationsMethodGenerator;

$baseUri = 'https://raw.githubusercontent.com/OAI/OpenAPI-Specification/main/examples/v3.0/petstore-expanded.json';
$folder = __DIR__ . '/build';
$namespace = 'Tests\\Prometee\\SwaggerClientGenerator\\Build';
$abstractOperationClass = \MyVendor\MyApi\AbstractOperations::class;
$throwClasses = [
    \MyVendorMyApi\piException => 'ApiException',
    \Http\Client\Exception => 'HttpClientException',
    \Symfony\Component\Serializer\Exception\ExceptionInterface => 'SerializerExceptionInterface',
];

// Instantiate the PHP class generator.
// It builds PHP classes from a given array config.
$phpGenerator = new PhpGenerator(
    new ClassBuilder(
        new ModelFactoryBuilder(),
        new ViewFactoryBuilder()
    )
);

// Instantiate the Open API model converter.
// It will convert model definitions to an array of config for the PhpGenerator.
$modelConverter = new ModelConverter(
    'Model',
    $namespace,
    new ModelHelper()
);

// Instantiate the Open API operations converter.
// It will create array of config for the PhpGenerator to create Operations classes.
// They will contain for example each "GET /pets" "GET /pets/{id}" methods to query the API. 
$operationsConverter = new OperationsConverter(
    'Operations',
    $namespace . "\\" . $modelConverter->getModelNamespacePrefix(),
    new OperationsHelper(),
    new OperationsMethodGenerator()
);

// 0.1. Configure the PHP Generator
$phpGenerator->configure($path, $namespace);
// 0.2. Configure the Operations classes with some default extending class
$operationsConverter->setAbstractOperationsClass($abstractOperationClass);
// 0.3. Configure the Operations classes with some PHPDoc @throw class on each generated methods
$operationsConverter->setThrowsClasses($throwClasses);

// 1. Convert OpenApi schema to an array understandable by the PhpGenerator
$openApiConverter = new OpenApiConverter($baseUri, $modelConverter, $operationsConverter);
$classConfig = $openApiConverter->convert();

// 2. Create PHP classes thank to the config given
$phpGenerator->setClassesConfig($classConfig);
$phpGenerator->generate();

```

[ico-version]: https://img.shields.io/packagist/v/Prometee/openapi-client-generator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-github-actions]: https://github.com/Prometee/OpenApiClientGenerator/workflows/Build/badge.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Prometee/openapi-client-generator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Prometee/openapi-client-generator
[link-github-actions]: https://github.com/Prometee/OpenApiClientGenerator/actions?query=workflow%3A"Build"
[link-code-quality]: https://scrutinizer-ci.com/g/Prometee/openapi-client-generator
