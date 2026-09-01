<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\Entity\Repository\UserTokenRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class DeleteAction
{
    public function __construct(
        private UserQueryBuilderRepository $userRepository,
        private UserTokenRepository        $userTokenRepository,
        private CurrentRoute               $currentRoute,
        private UrlGeneratorInterface      $urlGenerator,
        private ResponseFactoryInterface   $responseFactory,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$this->currentRoute->getArgument('id');

        $user = $this->userRepository->findOne($id);

        if ($user === null) {
            throw new \RuntimeException('User not found');
        }


        $this->userTokenRepository->delete(['id_user' => $id]);
        $this->userRepository->delete($id);

        return $this->responseFactory
            ->createResponse(303)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('user/index'),
            );
    }
}
