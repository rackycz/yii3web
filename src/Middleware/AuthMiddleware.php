<?php

namespace App\Middleware;

use App\Api\Shared\ResponseFactory;
use App\Entity\Repository\UserTokenRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Status;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserTokenRepository $tokenRepository,
        private readonly ResponseFactory     $responseFactory,
    )
    {
    }

    public function process(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return $this->responseFactory->fail(
                'Authentication token required',
                httpCode: Status::UNAUTHORIZED
            );
        }

        $userToken = $this->tokenRepository->findByToken($token);

        if (!$userToken) {
            return $this->responseFactory->fail(
                'Invalid or expired token',
                httpCode: Status::UNAUTHORIZED
            );
        }

        // Add user token to request attributes for later use
        $request = $request->withAttribute('token', $userToken);

        return $handler->handle($request);
    }

    private function extractToken(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
