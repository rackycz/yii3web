<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\Base\BaseRepository;
use App\Entity\UserToken;
use DateTimeImmutable;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Security\Random;

final class UserTokenRepository extends BaseRepository
{

    public function getTableName(): string
    {
        return 'user_token';
    }

    public function findByToken(string $token): ?UserToken
    {
        /** @var UserToken|null $tokenEntity */
        $tokenEntity = $this->findOne('token', $token);

        if (!$tokenEntity) {
            return null;
        }

        if ($tokenEntity->getExpiresAt() < new DateTimeImmutable()) {
            // Optionally delete expired token
            $this->delete($tokenEntity->getId());
            return null;
        }

        return $tokenEntity;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function deleteByUserId(int $userId): bool
    {
        try {
            $this->db->createCommand()
                ->delete($this->getTableName(), ['id_user' => $userId])
                ->execute();
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }

    public function create(int $userId, ?string $token = null, ?DateTimeImmutable $expiresAt = null, $lifespan = '+2 hours'): UserToken
    {
        if (!$token) {
            $token = bin2hex(Random::string(32));
            // Example: 654367506342505647634a6f4c6945784d793447355048734b364a4e62483743
        }
        if (!$expiresAt) {
            $expiresAt = (new DateTimeImmutable())->modify($lifespan);
        }

        $entity = UserToken::create($userId, $token, $expiresAt);
        $this->db->createCommand()
            ->insert($this->getTableName(), $entity->toArray())
            ->execute();

        return $entity;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function save(UserToken $model): void
    {
        $data = [
            'id_user' => $model->getName(),
            'token' => $model->getSurname(),
            'expires_at' => $model->getUsername(),
        ];

        if ($model->getId() === null) {
            $data['created_at'] = (new DateTimeImmutable())->format('Y-m-d H:i:s');
            $this->db->createCommand()
                ->insert($this->getTableName(), $data)
                ->execute();
        } else {
            $this->db->createCommand()
                ->update($this->getTableName(), $data, ['id' => $model->getId()])
                ->execute();
        }
    }


    public function hydrate(array $row): object
    {
        $model = new UserToken();

        $this->hydrateAttribute($model, 'id', (int)$row['id']);
        $this->hydrateAttribute($model, 'id_user', $row['id_user']);
        $this->hydrateAttribute($model, 'token', $row['token']);
        $this->hydrateAttribute($model, 'expires_at', new DateTimeImmutable($row['expires_at']));
        $this->hydrateAttribute($model, 'created_by', $row['created_by']);
        $this->hydrateAttribute($model, 'updated_by', $row['updated_by']);
        $this->hydrateAttribute($model, 'deleted_by', $row['deleted_by']);
        $this->hydrateAttribute($model, 'created_at', new DateTimeImmutable($row['created_at']));
        $this->hydrateAttribute($model, 'updated_at', new DateTimeImmutable($row['updated_at'] ?? ''));
        $this->hydrateAttribute($model, 'deleted_at', new DateTimeImmutable($row['deleted_by'] ?? ''));

        return $model;
    }
}
