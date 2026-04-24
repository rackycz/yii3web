<?php

declare(strict_types=1);

use App\Api\BearerAction;
use App\Api\IndexAction;
use App\Api\LoginAction;
use App\Middleware\AuthMiddleware;
use App\Web;
use Yiisoft\Csrf\CsrfTokenMiddleware;
use Yiisoft\DataResponse\Formatter\JsonFormatter;
use Yiisoft\DataResponse\Formatter\XmlFormatter;
use Yiisoft\DataResponse\Middleware\ContentNegotiatorDataResponseMiddleware;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Session\SessionMiddleware;

return [
    Group::create()
        // If CSRF is missing, the error "422 Unprocessable entity" will be returned.
        // Only needed in the web application.
        ->middleware(CsrfTokenMiddleware::class)
        // Session middleware is needed only for the web application.
        // REST API has no session.
        ->middleware(SessionMiddleware::class)
        ->routes(
            Route::get('/')
                ->action(Web\HomePage\Action::class)
                ->name('home'),
        ),
    Group::create('/api')
        ->middleware(
        // This ContentNegotiator is needed because of the API.
        // If it is missing, the error "Formatter is not set" is returned.
        // It formates the response by the "Accept" HTTP header to JSON or XML
        // ... usually JSON for Ajax and Postman, XML for the browser
            static fn() => new ContentNegotiatorDataResponseMiddleware(
                formatters: [
                    'application/xml' => new XmlFormatter(),
                    'application/json' => new JsonFormatter(),
                ],
                fallback: new JsonFormatter(),
            )
        )
        ->routes(
            Route::get('/')
                ->action(IndexAction::class)
                ->name('api/index'),
            Route::post('/login')
                ->action(LoginAction::class)
                ->name('app/login'),
            Route::get('/bearer')
                ->middleware(AuthMiddleware::class)
                ->action(BearerAction::class)->name('app/bearer'),
        ),
];
