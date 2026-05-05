<?php

declare(strict_types=1);

namespace App\Entity\QueryBuilder;

use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

final class UserQueryBuilderRepository
{
    public function __construct(
        private ConnectionInterface $db
    )
    {
    }

    public function findAll(array $filter): DataReaderInterface
    {
        $query = (new Query($this->db))
            ->select([
                'id',
                'name',
                'surname',
                'username',
                'phone',
                'email',
                'status',
                'email_verified_at'
            ])
            ->from('user')
            ->orderBy(['id' => 'ASC'])
            ->andFilterWhere($filter);

        return new IterableDataReader($query->all());
    }

    public function findOne(int $id): ?UserQueryBuilder
    {
        $data = (new Query($this->db))
            ->select([
                'id',
                'name',
                'surname',
                'username',
                'phone',
                'email',
                'status',
                'email_verified_at'
            ])
            ->from('user')
            ->where(['id' => $id])
            ->one();

        return $data ? UserQueryBuilder::fromArray($data) : null;
    }

    public function create(array $data): int
    {
        $this->db->createCommand()->insert('user', $data)->execute();
        return (int)$this->db->getLastInsertID();
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->createCommand()->update('user', $data, ['id' => $id])->execute() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->createCommand()->delete('user', ['id' => $id])->execute() > 0;
    }
}
