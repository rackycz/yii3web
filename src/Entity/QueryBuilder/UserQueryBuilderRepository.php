<?php

declare(strict_types=1);

namespace App\Entity\QueryBuilder;

use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

final class UserQueryBuilderRepository
{
    public function __construct(
        private ConnectionInterface $db
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
            ->andFilterWhere($filter);

        if ($sort) {
            $order = $sort->getOrder();
            if (!empty($order)) {
                $orderBy = [];
                foreach ($order as $field => $direction) {
                    $orderBy[$field] = $direction === 'desc' ? SORT_DESC : SORT_ASC;
                }
                $query = $query->orderBy($orderBy);
            }
        }

        $dataReader = new QueryDataReader($query, $sort);

        $paginator = (new OffsetPaginator($dataReader))
            ->withPageSize($pageSize)
            ->withCurrentPage($page);

        return $paginator;
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
