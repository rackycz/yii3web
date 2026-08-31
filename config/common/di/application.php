<?php

declare(strict_types=1);

use App\Middleware\CorsMiddleware;
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
    CorsMiddleware::class => [
        '__construct()' => [
            'allowedOrigins' => [
                ...(isset($_ENV['CORS_ALLOWED_ORIGINS'])
                    ? array_map('trim', explode(',', $_ENV['CORS_ALLOWED_ORIGINS']))
                    : ['*']),
            ],
            'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With'],
            'allowCredentials' => isset($_ENV['CORS_ALLOW_CREDENTIALS'])
                ? filter_var($_ENV['CORS_ALLOW_CREDENTIALS'], FILTER_VALIDATE_BOOLEAN)
                : false,
            'maxAge' => isset($_ENV['CORS_MAX_AGE'])
                ? (int)$_ENV['CORS_MAX_AGE']
                : 86400,
        ],
    ],
];
