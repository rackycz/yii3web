<?php

declare(strict_types=1);

use App\Shared\ApplicationParams;
use App\Shared\PasswordService;
use Yiisoft\Security\PasswordHasher;

/** @var array $params */

return [
    ApplicationParams::class => [
        '__construct()' => [
            'name' => $params['application']['name'],
            'charset' => $params['application']['charset'],
            'locale' => $params['application']['locale'],
        ],
    ],
    PasswordService::class => [
        '__construct()' => [
            'hasher' => new PasswordHasher(),
        ],
    ],
];
