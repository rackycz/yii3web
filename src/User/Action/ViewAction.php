<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class ViewAction
{
    public function __construct(
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
        private CurrentRoute               $currentRoute,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $arguments = $this->currentRoute->getArguments();
        $id = (int)($arguments['id'] ?? 0);

        if ($id === 0) {
            throw new \RuntimeException('Invalid ID');
        }

        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            // Handle 404 - you might want to throw an exception or return a 404 response
            throw new \RuntimeException('User not found');
        }

        return $this->viewRenderer->render('User/View/view', [
            'user' => $user,
        ]);
    }
}
