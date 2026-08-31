<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\User\Form\UserUpdateForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class UpdateAction
{
    public function __construct(
        private CurrentRoute               $currentRoute,
        private WebViewRenderer            $viewRenderer,
        private UserQueryBuilderRepository $userRepository,
        private ValidatorInterface         $validator,
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
        $form->setName($user->getName());
        $form->setSurname($user->getSurname());
        $form->setUsername($user->getUsername());
        $form->setEmail($user->getEmail());
        $form->setPhone($user->getPhone());
        $form->setStatus($user->getStatus());

        if ($request->getMethod() === 'POST') {
            $form->populate($request->getParsedBody()['UserUpdateForm'] ?? []);
            $result = $this->validator->validate($form);

            if ($result->isValid()) {
                $updateData = [
                    'name' => $form->getName(),
                    'surname' => $form->getSurname(),
                    'username' => $form->getUsername(),
                    'email' => $form->getEmail(),
                    'phone' => $form->getPhone(),
                    'status' => $form->getStatus(),
                ];

                $this->userRepository->update($id, $updateData);

                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->urlGenerator->generate('user/index'));
            }
        }

        return $this->viewRenderer->render('User/View/update', [
            'user' => $user,
            'form' => $form,
            'validationErrors' => [],
        ]);
    }
}
