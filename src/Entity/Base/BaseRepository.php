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
    public function findAll(mixed $condition = null, array $orderBy = [], $asArray = false): array
    {
        $query = (new Query($this->db))
            ->select('*')
            ->from($this->getTableName());

        if ($condition) {
            $query->andFilterWhere($condition);
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

    /**
     * @param string $attr
     * @param mixed $value
     * @return array|object|null
     */
    public function findOne(array $condition): array|object|null
    {
        try {
            return (new Query($this->db))
                ->select('*')
                ->from($this->getTableName())
                ->andFilterWhere($condition)
                ->one();
        } catch (\Throwable $e) {
            return null;
        }
    }


    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function delete(array $condition): bool
    {
        try {
            $this->db->createCommand()
                ->delete($this->getTableName(), $condition)
                ->execute();
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }

    /**
     * Populates a specific attribute of the provided model with the given value.
     *
     * Usage:
     * $model = new User();
     * $this->hydrateAttribute($model, 'id', (int)$row['id']);
     * ... etc
     *
     * You can also do this instead:
     * $model = new User();
     * $model->setId((int)$data['id']);
     * ... etc
     *
     * @param object $model The object whose attribute is to be populated.
     * @param mixed $attribute The name of the attribute to be populated.
     * @param mixed $value The value to assign to the specified attribute.
     * @return void
     */
    protected function hydrateAttribute(object $model, mixed $attribute, mixed $value)
    {
        $reflection = new \ReflectionClass($model);
        $idProperty = $reflection->getProperty($attribute);
        $idProperty->setAccessible(true);
        $idProperty->setValue($model, $value);
    }
}
