<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * CORS middleware for API requests.
 * =================================
 * There are more ways how to use the DI.
 * I picked the way "creating and setting private properties in constructor". The advantages are:
 * - If properties were public, they could be changed after object creation without any control.
 * - If setters (even with validation) were used, the developer could still set an inconsistent state after the creation.
 * - If values are required by the constructor, they must be entered. Setters and public properties are for optional values.
 * =====
 * PS: You can also use Yii3 class CorsAllowAllMiddleware, but it is not recommended for production.
 * @link https://www.yiiframework.com/doc/api/3.0/http-middleware/yiisoft-httpmiddleware-corsallowallmiddleware
 */
final readonly class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory, // Instance is provided automatically by the Yii3 DI container thanks to config/web/di/psr17.php
        private array                    $allowedOrigins = ['*'],
        private array                    $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        private array                    $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With'],
        private bool                     $allowCredentials = false,
        private int                      $maxAge = 86400,
    )
    {
    }

    public function process(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $origin = $request->getHeaderLine('Origin');

        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            $response = $this->responseFactory->createResponse(204);
            return $this->addCorsHeaders($response, $origin);
        }

        $response = $handler->handle($request);

        // Add CORS headers to the response
        return $this->addCorsHeaders($response, $origin);
    }

    private function addCorsHeaders(ResponseInterface $response, string $origin): ResponseInterface
    {
        $allowedOrigin = $this->getAllowedOrigin($origin);

        if ($allowedOrigin !== null) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $allowedOrigin);
        }

        return $response
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders))
            ->withHeader('Access-Control-Max-Age', (string)$this->maxAge)
            ->withHeader('Access-Control-Allow-Credentials', $this->allowCredentials ? 'true' : 'false');
    }

    private function getAllowedOrigin(string $origin): ?string
    {
        if (in_array('*', $this->allowedOrigins, true)) {
            return '*';
        }

        if (in_array($origin, $this->allowedOrigins, true)) {
            return $origin;
        }

        return null;
    }
}
