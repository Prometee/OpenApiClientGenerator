<?php

declare(strict_types=1);

namespace Prometee\SwaggerClientGenerator\Base\View;

interface ClassViewInterface extends ViewInterface
{
    /**
     * @return string
     */
    public function buildBody(): string;

    /**
     * @return string|null
     */
    public function buildSignature(): ?string;

}