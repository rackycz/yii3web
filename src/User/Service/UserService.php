<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Entity\QueryBuilder\UserQueryBuilder;
use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\Entity\Repository\UserTokenRepository;
use App\User\Form\UserUpdateForm;
use RuntimeException;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Validator\ValidatorInterface;

final readonly class UserService
{
    public function __construct(
        private UserQueryBuilderRepository $userRepository,
        private UserTokenRepository        $userTokenRepository,
        private ValidatorInterface         $validator,
    )
    {
    }

    public function findAll(
        array $filter,
        ?Sort $sort = null,
        int   $page = 1,
        int   $pageSize = 10
    ): ReadableDataInterface
    {
        return $this->userRepository->findAll($filter, $sort, $page, $pageSize);
    }

    public function findOne(int $id): ?UserQueryBuilder
    {
        return $this->userRepository->findOne($id);
    }

    public function create(array $data): int
    {
        return $this->userRepository->create($data);
    }

    public function createFromForm(UserUpdateForm $form): int
    {
        $result = $this->validator->validate($form);
        if (!$result->isValid()) {
            throw new RuntimeException('Validation failed: ' . implode(', ', array_map(fn($e) => $e->getMessage(), $result->getErrors())));
        }

        return $this->create([
            'name' => $form->name,
            'surname' => $form->surname,
            'username' => $form->username,
            'email' => $form->email,
            'phone' => $form->phone,
            'status' => $form->status,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            throw new RuntimeException("User $id not found");
        }

        return $this->userRepository->update($id, $data);
    }

    public function updateFromForm(int $id, UserUpdateForm $form): bool
    {
        $result = $this->validator->validate($form);
        if (!$result->isValid()) {
            throw new RuntimeException('Validation failed: ' . implode(', ', array_map(fn($e) => $e->getMessage(), $result->getErrors())));
        }

        return $this->update($id, [
            'name' => $form->name,
            'surname' => $form->surname,
            'username' => $form->username,
            'email' => $form->email,
            'phone' => $form->phone,
            'status' => $form->status,
        ]);
    }

    public function delete(int $id): bool
    {
        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            throw new RuntimeException("User $id not found");
        }

        $this->userTokenRepository->delete(['id_user' => $id]);
        return $this->userRepository->delete($id);
    }
}
