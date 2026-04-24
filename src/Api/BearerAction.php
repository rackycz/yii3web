<?php
declare(strict_types=1);

namespace App\Api;

use App\Api\Shared\ResponseFactory;
use App\Entity\Repository\UserRepository;
use App\Entity\Repository\UserTokenRepository;
use App\Shared\ApplicationParams;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BearerAction
{
    public function __construct(
        private UserRepository      $userRepository,
        private UserTokenRepository $userTokenRepository,
    )
    {
    }

    public function __invoke(
        ResponseFactory        $responseFactory,
        ApplicationParams      $applicationParams,
        ContainerInterface     $container,
        ServerRequestInterface $request
    ): ResponseInterface
    {
        // See the AuthMiddleware in routes.php
        return $responseFactory->success(['Bearer token found and valid']);
    }

}
