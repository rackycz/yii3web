<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class UpdateAction
{
    public function __construct(
        private CurrentRoute               $currentRoute,
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$this->currentRoute->getArgument('id');

        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            throw new \RuntimeException("User $id not found");
        }

        return $this->viewRenderer->render('User/View/update', [
            'user' => $user,
        ]);
    }
}
