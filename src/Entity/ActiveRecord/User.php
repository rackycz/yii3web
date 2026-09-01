<?php

declare(strict_types=1);

namespace App\Entity\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveRecord;

final class User extends ActiveRecord
{
    public ?int $id = null;
    public string $name;
    public string $surname;
    public ?string $username = null;
    public ?string $phone = null;
    public string $email;
    public ?\DateTimeImmutable $email_verified_at = null;
    public int $status = 100;
    public ?int $created_by = null;
    public ?int $updated_by = null;
    public ?int $deleted_by = null;
    public ?\DateTimeImmutable $created_at = null;
    public ?\DateTimeImmutable $updated_at = null;
    public ?\DateTimeImmutable $deleted_at = null;

    public function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * Techically useless, but is used in src/User/View/update.php
     * This demo shows both approaches - ActiveRecord and QueryBuilder
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


}
