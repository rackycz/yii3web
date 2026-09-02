<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Service\UserService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Method;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;

final readonly class DeleteAction
{
    public function __construct(
        private UserService              $userService,
        private CurrentRoute             $currentRoute,
        private UrlGeneratorInterface    $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
        private Flash                    $flash,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== Method::POST) {
            throw new \RuntimeException('Only POST requests are allowed');
        }

        $id = (int)$this->currentRoute->getArgument('id');

        $this->userService->delete($id);

        $this->flash->add('success', 'User deleted successfully');

        return $this->responseFactory
            ->createResponse(303)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('user/index'),
            );
    }
}
