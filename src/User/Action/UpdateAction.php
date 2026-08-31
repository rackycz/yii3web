<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\User\Form\UserUpdateForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class UpdateAction
{
    public function __construct(
        private CurrentRoute               $currentRoute,
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
        private FormHydrator               $formHydrator,
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
            throw new \RuntimeException("User $id not found");
        }

        $form = new UserUpdateForm();

        $form->name = $user->getName();
        $form->surname = $user->getSurname();
        $form->username = $user->getUsername();
        $form->email = $user->getEmail();
        $form->phone = $user->getPhone();
        $form->status = $user->getStatus();

        if ($request->getMethod() === 'POST') {

            $isValid = $this->formHydrator->populateAndValidate($form, $request->getParsedBody());

            if ($isValid) {
                $this->userRepository->update($id, [
                    'name' => $form->name,
                    'surname' => $form->surname,
                    'username' => $form->username,
                    'email' => $form->email,
                    'phone' => $form->phone,
                    'status' => $form->status,
                ]);

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader(
                        'Location',
                        $this->urlGenerator->generate('user/index'),
                    );
            }
        }

        return $this->viewRenderer->render('User/View/update', [
            'user' => $user,
            'form' => $form,
            'validationErrors' => $form->isValidated() ? $form->getValidationResult()->getErrors() : [],
        ]);
    }
}
