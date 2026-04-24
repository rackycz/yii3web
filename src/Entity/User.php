<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Base\BlameableEntity;
use App\Entity\Repository\UserRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use DateTimeImmutable;
use Yiisoft\Security\PasswordHasher;

#[Entity(repository: UserRepository::class)]
class User extends BlameableEntity
{
    /**
     * Primary key.
     * Null for new unsaved entities, positive integer after saving to database.
     */
    #[Column(type: 'primary')]
    private ?int $id = null;

    /**
     * User's first name.
     */
    #[Column(type: 'string(255)')]
    private string $name = '';

    /**
     * User's last name.
     */
    #[Column(type: 'string(255)')]
    private string $surname = '';

    /**
     * Optional username for login.
     */
    #[Column(type: 'string(255)', nullable: true)]
    private ?string $username = null;

    /**
     * Optional phone number.
     */
    #[Column(type: 'string(255)', nullable: true)]
    private ?string $phone = null;

    /**
     * User's email address (unique).
     */
    #[Column(type: 'string(255)')]
    private string $email = '';

    /**
     * Timestamp when email was verified.
     */
    #[Column(type: 'datetime', nullable: true)]
    private ?DateTimeImmutable $email_verified_at = null;

    /**
     * User status (default: 100).
     */
    #[Column(type: 'integer')]
    private int $status = 100;

    public function validatePassword(string $password, string $expectedHash): bool
    {
        return (new PasswordHasher())->validate($password, $expectedHash);
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailVerifiedAt(): ?DateTimeImmutable
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?DateTimeImmutable $email_verified_at): self
    {
        $this->email_verified_at = $email_verified_at;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
