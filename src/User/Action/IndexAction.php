<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class IndexAction
{
    public function __construct(
        private WebViewRenderer       $viewRenderer,
        private UserService           $userService,
        private UrlGeneratorInterface $urlGenerator,
        private Flash                 $flash
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $sort = Sort::only([
            'id',
            'name',
            'surname',
            'username',
            'email',
            'phone',
            'status',
        ]);

        if (isset($queryParams['sort'])) {
            $sort = $sort->withOrderString((string)$queryParams['sort']);
        }

        $filterParams = $queryParams;
        unset($filterParams['sort'], $filterParams['page'], $filterParams['pagesize']);

        $page = max(1, (int)($queryParams['page'] ?? 1));
        $pageSize = (int)($queryParams['pagesize'] ?? 1);

        $dataProvider = $this->userService->findAll(
            $filterParams,
            $sort,
            $page,
            $pageSize
        );

        return $this->viewRenderer->render('User/View/index', [
            'dataProvider' => $dataProvider,
            'urlGenerator' => $this->urlGenerator,
            'flash' => $this->flash,
        ]);
    }
}
