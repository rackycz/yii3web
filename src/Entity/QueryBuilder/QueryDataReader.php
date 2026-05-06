<?php

declare(strict_types=1);

namespace App\Entity\QueryBuilder;

use IteratorAggregate;
use Traversable;
use Yiisoft\Data\Reader\CountableDataInterface;
use Yiisoft\Data\Reader\LimitableDataInterface;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Data\Reader\SortableDataInterface;
use Yiisoft\Db\Query\Query;

/**
 * Efficient data reader that works directly with Query for pagination
 */
final class QueryDataReader implements CountableDataInterface, LimitableDataInterface, OffsetableDataInterface, SortableDataInterface, IteratorAggregate
{
    private Query $query;
    private ?int $limit = null;
    private ?int $offset = null;
    private ?int $count = null;
    private ?Sort $sort = null;

    public function __construct(
        Query $query,
        ?Sort $sort = null
    )
    {
        $this->query = $query;
        $this->sort = $sort;
    }

    public function getIterator(): Traversable
    {
        $query = clone $this->query;

        if ($this->sort !== null) {
            $order = $this->sort->getOrder();
            if (!empty($order)) {
                $orderBy = [];
                foreach ($order as $field => $direction) {
                    $orderBy[$field] = $direction === 'desc' ? SORT_DESC : SORT_ASC;
                }
                $query = $query->orderBy($orderBy);
            }
        }

        if ($this->offset !== null) {
            $query = $query->offset($this->offset);
        }

        if ($this->limit !== null) {
            $query = $query->limit($this->limit);
        }

        return new \ArrayIterator($query->all());
    }

    public function count(): int
    {
        if ($this->count === null) {
            $countQuery = clone $this->query;
            $this->count = $countQuery->count();
        }

        return $this->count;
    }

    public function withLimit(?int $limit): static
    {
        $new = clone $this;
        $new->limit = $limit;
        return $new;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function withOffset(?int $offset): static
    {
        $new = clone $this;
        $new->offset = $offset;
        return $new;
    }

    public function getOffset(): int
    {
        return $this->offset ?? 0;
    }

    public function read(): iterable
    {
        return $this->getIterator();
    }

    public function readOne(): object|array|null
    {
        $iterator = $this->getIterator();
        $iterator->rewind();
        return $iterator->valid() ? $iterator->current() : null;
    }

    public function withSort(?\Yiisoft\Data\Reader\Sort $sort): static
    {
        $new = clone $this;
        $new->sort = $sort;
        return $new;
    }

    public function getSort(): ?\Yiisoft\Data\Reader\Sort
    {
        return $this->sort;
    }
}
