<?php

declare(strict_types=1);

namespace App\Entity\Base;

use App\Entity\User;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Query\Query;

abstract class BaseRepository
{
    abstract public function getTableName(): string;

    abstract public function hydrate(array $row): object;

    public function __construct(
        protected readonly ConnectionInterface $db
    )
    {
    }

    /**
     * @return array<User>
     */
    public function findAll(mixed $where = null, array $orderBy = [], $asArray = false): array
    {
        $query = (new Query($this->db))
            ->select('*')
            ->from($this->getTableName());

        if ($where) {
            $query->andFilterWhere($where);
        }

        if (!empty($orderBy)) {
            $query->orderBy($orderBy);
        }

        try {
            $data = $query->all();
        } catch (\Throwable $e) {
            $data = [];
        }

        if ($asArray) {
            return $data;
        }

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $data
        );
    }

    public function findOne(string $attr, mixed $value): ?object
    {
        try {
            $row = (new Query($this->db))
                ->select('*')
                ->from($this->getTableName())
                ->andFilterWhere([$attr => $value])
                ->one();
        } catch (\Throwable $e) {
            return null;
        }

        return $row ? $this->hydrate($row) : null;
    }


    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function delete(int $id): bool
    {
        try {
            $this->db->createCommand()
                ->delete($this->getTableName(), ['id' => $id])
                ->execute();
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }

    protected function hydrateAttribute(object $model, mixed $attribute, mixed $value)
    {
        $reflection = new \ReflectionClass($model);
        $idProperty = $reflection->getProperty($attribute);
        $idProperty->setAccessible(true);
        $idProperty->setValue($model, $value);
    }
}
