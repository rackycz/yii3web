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

    public function findByUsername(string $username): object
    {
        return $this->findOne('username', $username);
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


    public function hydrate(array $row): object
    {
        $model = new User();

        $this->hydrateAttribute($model, 'id', (int)$row['id']);
        $this->hydrateAttribute($model, 'name', ($row['name']));
        $this->hydrateAttribute($model, 'surname', $row['surname']);
        $this->hydrateAttribute($model, 'username', $row['username']);
        $this->hydrateAttribute($model, 'phone', $row['phone']);
        $this->hydrateAttribute($model, 'email', $row['email']);
        $this->hydrateAttribute($model, 'email_verified_at', new DateTimeImmutable($row['email_verified_at'] ?? ''));
        $this->hydrateAttribute($model, 'status', (int)$row['status']);
        $this->hydrateAttribute($model, 'created_by', $row['created_by']);
        $this->hydrateAttribute($model, 'updated_by', $row['updated_by']);
        $this->hydrateAttribute($model, 'deleted_by', $row['deleted_by']);
        $this->hydrateAttribute($model, 'created_at', new DateTimeImmutable($row['created_at']));
        $this->hydrateAttribute($model, 'updated_at', new DateTimeImmutable($row['updated_at'] ?? ''));
        $this->hydrateAttribute($model, 'deleted_at', new DateTimeImmutable($row['deleted_by'] ?? ''));

        return $model;
    }
}
