<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientBuilder\Swagger\Builder;

use Prometee\SwaggerClientBuilder\Builder\GeneratorBuilder;
use Prometee\SwaggerClientBuilder\GeneratorInterface;
use Prometee\SwaggerClientBuilder\Swagger\Factory\MethodFactory;
use Prometee\SwaggerClientBuilder\Swagger\Factory\MethodFactoryInterface;
use Prometee\SwaggerClientBuilder\Swagger\PhpBuilder\OperationMethodBuilder;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerModelGeneratorInterface;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGenerator;
use Prometee\SwaggerClientBuilder\Swagger\SwaggerOperationsGeneratorInterface;

class SwaggerGeneratorBuilder extends GeneratorBuilder implements SwaggerGeneratorBuilderInterface
{
    /** @var MethodFactoryInterface */
    protected $swaggerMethodFactory;
    /** @var SwaggerModelGeneratorInterface */
    protected $swaggerModelGenerator;
    /** @var SwaggerOperationsGeneratorInterface */
    protected $swaggerOperationsGenerator;

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->swaggerMethodFactory = new MethodFactory(
            $this->phpDocFactory,
            OperationMethodBuilder::class
        );

        $this->swaggerModelGenerator = new SwaggerModelGenerator(
            $this->classFactory,
            $this->methodFactory
        );
        $this->swaggerOperationsGenerator = new SwaggerOperationsGenerator(
            $this->classFactory,
            $this->methodFactory,
            $this->swaggerMethodFactory
        );
    }

    /**
     * @inheritDoc
     */
    public function build(): GeneratorInterface
    {
        return new SwaggerGenerator(
            $this->swaggerModelGenerator,
            $this->swaggerOperationsGenerator
        );
    }

    /**
     * @inheritDoc
     */
    public function getSwaggerModelGenerator(): SwaggerModelGeneratorInterface
    {
        return $this->swaggerModelGenerator;
    }

    /**
     * @inheritDoc
     */
    public function setSwaggerModelGenerator(SwaggerModelGeneratorInterface $swaggerModelGenerator): void
    {
        $this->swaggerModelGenerator = $swaggerModelGenerator;
    }

    /**
     * @inheritDoc
     */
    public function getSwaggerOperationsGenerator(): SwaggerOperationsGeneratorInterface
    {
        return $this->swaggerOperationsGenerator;
    }

    /**
     * @inheritDoc
     */
    public function setSwaggerOperationsGenerator(SwaggerOperationsGeneratorInterface $swaggerOperationsGenerator): void
    {
        $this->swaggerOperationsGenerator = $swaggerOperationsGenerator;
    }
}