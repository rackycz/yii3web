<?php

declare(strict_types=1);

namespace App\User\Action;

use App\User\Form\UserUpdateForm;
use App\User\Service\UserService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Method;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Validator\Error;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class UpdateAction
{
    public function __construct(
        private CurrentRoute             $currentRoute,
        private WebViewRenderer          $viewRenderer,
        private UserService              $userService,
        private FormHydrator             $formHydrator,
        private UrlGeneratorInterface    $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$this->currentRoute->getArgument('id');

        $user = $this->userService->findOne($id);

        if ($user === null) {
            throw new RuntimeException("User $id not found");
        }

        $form = new UserUpdateForm();
        $this->formHydrator->populate(
            $form,
            $user->toArray(),
            scope: ''
        );

        $validationErrors = [];

        if ($request->getMethod() === Method::POST) {
            $this->formHydrator->populateFromPost($form, $request);

            try {
                $this->userService->updateFromForm($id, $form);

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('user/index'),
                    );
            } catch (RuntimeException $e) {
                $validationErrors = [new Error($e->getMessage())];
            }
        }

        return $this->viewRenderer->render('User/View/update', [
            'user' => $user,
            'form' => $form,
            'validationErrors' => $validationErrors,
            'note' => 'QueryBuilder demo (see routes.php)',
        ]);
    }
}
