<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\Base\BaseRepository;
use App\Entity\User;
use DateTimeImmutable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;

final class UserRepository extends BaseRepository
{

    public function getTableName(): string
    {
        return 'user';
    }

    public function findByUsername(string $username): ?User
    {
        $record = $this->findOne(['username' => $username]);
        if (!$record) {
            return null;
        }
        return $this->hydrate($record);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function save(User $user): void
    {
        $data = [
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ];

        if ($user->getId() === null) {
            $data['created_at'] = (new DateTimeImmutable())->format('Y-m-d H:i:s');
            $this->db->createCommand()
                ->insert($this->getTableName(), $data)
                ->execute();
        } else {
            $this->db->createCommand()
                ->update($this->getTableName(), $data, ['id' => $user->getId()])
                ->execute();
        }
    }


    /**
     * @throws \Exception
     */
    public function hydrate(array $row): object
    {
        $model = new User();
        $model->setId((int)$row['id']);
        $model->setName(($row['name']));
        $model->setSurname($row['surname']);
        $model->setUsername($row['username']);
        $model->setPhone($row['phone']);
        $model->setEmail($row['email']);
        $model->setEmailVerifiedAt(new DateTimeImmutable($row['email_verified_at'] ?? ''));
        $model->setStatus((int)$row['status']);
        $model->setCreatedBy($row['created_by']);
        $model->setUpdatedBy($row['updated_by']);
        $model->setDeletedBy($row['deleted_by']);
        $model->setCreatedAt(new DateTimeImmutable($row['created_at'] ?? ''));
        $model->setUpdatedAt(new DateTimeImmutable($row['updated_at'] ?? ''));
        $model->setDeletedAt(new DateTimeImmutable($row['deleted_by'] ?? ''));

        return $model;
    }
}
