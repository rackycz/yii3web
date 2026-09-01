<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\User\Form\UserUpdateForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class CreateAction
{
    public function __construct(
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
        private FormHydrator               $formHydrator,
        private UrlGeneratorInterface      $urlGenerator,
        private ResponseFactoryInterface   $responseFactory,
        private Flash                      $flash,
    )
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $form = new UserUpdateForm();

        if ($request->getMethod() === Method::POST) {
            $isValid = $this->formHydrator->populateFromPostAndValidate($form, $request);

            if ($isValid) {
                $this->userRepository->create([
                    'name' => $form->name,
                    'surname' => $form->surname,
                    'username' => $form->username,
                    'email' => $form->email,
                    'phone' => $form->phone,
                    'status' => $form->status,
                ]);

                $this->flash->add('success', 'User created successfully');

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('user/index'),
                    );
            }
        }

        return $this->viewRenderer->render('User/View/create', [
            'form' => $form,
            'validationErrors' => $form->isValidated() ? $form->getValidationResult()->getErrors() : [],
        ]);
    }
}
