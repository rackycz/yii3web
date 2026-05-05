<?php

declare(strict_types=1);

namespace App\Entity\QueryBuilder;

final class UserQueryBuilder
{
    public function __construct(
        private array $data = []
    )
    {
    }

    public function getId(): int
    {
        return (int)($this->data['id'] ?? null);
    }

    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }

    public function getSurname(): string
    {
        return $this->data['surname'] ?? '';
    }

    public function getUsername(): string
    {
        return $this->data['username'] ?? '';
    }

    public function getPhone(): string
    {
        return $this->data['phone'] ?? '';
    }

    public function getEmail(): string
    {
        return $this->data['email'] ?? '';
    }

    public function getStatus(): int
    {
        return (int)($this->data['status'] ?? 100);
    }

    public function getEmailVerifiedAt(): string
    {
        return $this->data['email_verified_at'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
