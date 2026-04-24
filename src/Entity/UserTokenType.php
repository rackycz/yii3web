<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Base\BlameableEntity;
use DateTimeImmutable;

#[Table(name: 'user_token')]
final class UserTokenType extends BlameableEntity
{
    public const WEB_PASSWORD_HASH = 1;
    public const WEB_PASSWORD_DEFAULT = 2;
    public const API_PASSWORD_HASH = 3;
    public const API_PASSWORD_DEFAULT = 4;
    public const EMAIL_VERIFY = 5;
    public const API_BEARER = 6;

    #[Column(type: 'primary')]
    private ?int $id = null;

    #[Column(type: 'integer', notNull: true)]
    private int $id_user;

    #[Column(type: 'string', notNull: true, unique: true)]
    private string $token;

    #[Column(type: 'datetime', notNull: true)]
    private DateTimeImmutable $expires_at;

    public static function create(int $userId, string $token, DateTimeImmutable $expiresAt = null): UserTokenType
    {
        $userToken = new UserTokenType();
        $userToken->id_user = $userId;
        $userToken->token = $token;
        $userToken->expires_at = $expiresAt;
        return $userToken;
    }

    public function toArray(): array
    {
        return [
            'id_user' => $this->id_user ?? null,
            'token' => $this->token,
            'expires_at' => $this->expires_at->format('Y-m-d H:i:s'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expires_at;
    }

    public function setExpiresAt(DateTimeImmutable $expiresAt): self
    {
        $this->expires_at = $expiresAt;
        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expires_at < new DateTimeImmutable();
    }
}
