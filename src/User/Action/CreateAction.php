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
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Validator\Error;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class CreateAction
{
    public function __construct(
        private WebViewRenderer          $viewRenderer,
        private UserService              $userService,
        private FormHydrator             $formHydrator,
        private UrlGeneratorInterface    $urlGenerator,
        private ResponseFactoryInterface $responseFactory,
        private Flash                    $flash,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new UserUpdateForm();
        $validationErrors = [];

        if ($request->getMethod() === Method::POST) {
            $this->formHydrator->populateFromPost($form, $request);

            try {
                $this->userService->createFromForm($form);

                $this->flash->add('success', 'User created successfully');

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

        return $this->viewRenderer->render('User/View/create', [
            'form' => $form,
            'validationErrors' => $validationErrors,
        ]);
    }
}
