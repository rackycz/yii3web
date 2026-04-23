<?php

declare(strict_types=1);

namespace App\Web\HomePage;

use App\Entity\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ContainerInterface $container,
    ): ResponseInterface
    {

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);
        $users = $userRepository->findAll([], [], true);
        return $this->viewRenderer->render(__DIR__ . '/template', [
            'users' => $users,
        ]);
    }
}
