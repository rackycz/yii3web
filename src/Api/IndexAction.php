<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Shared\ResponseFactory;
use App\Entity\Repository\UserRepository;
use App\Shared\ApplicationParams;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

final class IndexAction
{
    public function __invoke(
        ResponseFactory    $responseFactory,
        ApplicationParams  $applicationParams,
        ContainerInterface $container,
    ): ResponseInterface
    {
        try {
            $userRepository = $container->get(UserRepository::class);
        } catch (\Throwable $e) {
            return $responseFactory->success([]);
        }
        return $responseFactory->success($userRepository->findAll([], [], true));
    }
}
