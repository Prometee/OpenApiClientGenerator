<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $config): void {
    $config->import('vendor/symplify/easy-coding-standard/config/set/psr12.php');

    $config->parallel();
    $config->paths(['src', 'tests']);
};
