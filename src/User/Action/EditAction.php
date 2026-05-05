<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class EditAction
{
    public function __construct(
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');

        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            throw new \RuntimeException('User not found');
        }

        return $this->viewRenderer->render('@user/update', [
            'user' => $user,
        ]);
    }
}
