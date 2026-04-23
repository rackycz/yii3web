<?php

declare(strict_types=1);

namespace App\Entity\Base;

use Cycle\Annotated\Annotation\Column;
use DateTimeImmutable;

abstract class BlameableEntity
{
    /**
     * Reference to user who created this record.
     */
    #[Column(type: 'integer', nullable: true)]
    private ?int $created_by = null;

    /**
     * Reference to user who last updated this record.
     */
    #[Column(type: 'integer', nullable: true)]
    private ?int $updated_by = null;

    /**
     * Reference to user who deleted this record.
     */
    #[Column(type: 'integer', nullable: true)]
    private ?int $deleted_by = null;

    /**
     * Record creation timestamp.
     */
    #[Column(type: 'datetime')]
    private DateTimeImmutable $created_at;

    /**
     * Last update timestamp.
     */
    #[Column(type: 'datetime', nullable: true)]
    private ?DateTimeImmutable $updated_at = null;

    /**
     * Deletion timestamp.
     */
    #[Column(type: 'datetime', nullable: true)]
    private ?DateTimeImmutable $deleted_at = null;


    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(?int $created_by): self
    {
        $this->created_by = $created_by;
        return $this;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?int $updated_by): self
    {
        $this->updated_by = $updated_by;
        return $this;
    }

    public function getDeletedBy(): ?int
    {
        return $this->deleted_by;
    }

    public function setDeletedBy(?int $deleted_by): self
    {
        $this->deleted_by = $deleted_by;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): self
    {
        $this->deleted_at = $deletedAt;
        return $this;
    }
}
