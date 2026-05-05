<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class IndexAction
{
    public function __construct(
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $dataProvider = $this->userRepository->findAll($request->getQueryParams());

        return $this->viewRenderer->render('User/View/index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
