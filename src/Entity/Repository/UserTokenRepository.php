<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use App\Entity\Base\BaseRepository;
use App\Entity\UserToken;
use App\Entity\UserTokenType;
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
        $record = $this->findOne(['token' => $token]);

        if (!$record) {
            return null;
        }

        $token = $this->hydrate($record);

        if ($token->getExpiresAt() < new DateTimeImmutable()) {
            $this->delete($token->getId());
            return null;
        }

        return $token;
    }

    public function createApiBearer(int $userId, ?string $token = null, ?DateTimeImmutable $expiresAt = null, $lifespan = '+2 hours'): UserToken
    {
        if (!$token) {
            $token = bin2hex(Random::string(32));
            // Example: 654367506342505647634a6f4c6945784d793447355048734b364a4e62483743
        }
        if (!$expiresAt) {
            $expiresAt = (new DateTimeImmutable())->modify($lifespan);
        }

        $token = UserToken::create($userId, $token, UserTokenType::API_BEARER, $expiresAt);
        $this->db->createCommand()
            ->insert($this->getTableName(), $token->toArray())
            ->execute();

        return $token;
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

        $model->setId((int)$row['id']);
        $model->setIdUser((int)$row['id_user']);
        $model->setToken($row['token']);
        $model->setExpiresAt(new DateTimeImmutable($row['expires_at'] ?? ''));
        $model->setcreatedBy($row['created_by']);
        $model->setupdatedBy($row['updated_by']);
        $model->setdeletedBy($row['deleted_by']);
        $model->setcreatedAt(new DateTimeImmutable($row['created_at'] ?? ''));
        $model->setupdatedAt(new DateTimeImmutable($row['updated_at'] ?? ''));
        $model->setdeletedAt(new DateTimeImmutable($row['deleted_by'] ?? ''));

        return $model;
    }

    public function findTokenByType(int $userId, int $tokenType): ?string
    {
        $record = $this->findOne([
            'id_user' => $userId,
            'id_type' => $tokenType,
        ]);
        if (!$record) {
            return null;
        }
        $userToken = $this->hydrate($record);
        return $userToken->getToken();
    }
}
