<?php

declare(strict_types=1);

namespace App\User\Service;

use App\Entity\QueryBuilder\UserQueryBuilder;
use App\Entity\QueryBuilder\UserQueryBuilderRepository;
use App\Entity\Repository\UserTokenRepository;
use RuntimeException;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Data\Reader\Sort;

final readonly class UserService
{
    public function __construct(
        private UserQueryBuilderRepository $userRepository,
        private UserTokenRepository        $userTokenRepository,
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

    public function update(int $id, array $data): bool
    {
        $user = $this->userRepository->findOne($id);
        if ($user === null) {
            throw new RuntimeException("User $id not found");
        }

        return $this->userRepository->update($id, $data);
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
