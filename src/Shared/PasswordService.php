<?php

declare(strict_types=1);

namespace App\Shared;

use Yiisoft\Security\PasswordHasher;

final readonly class PasswordService
{
    public function __construct(
        private PasswordHasher $hasher,
    )
    {
    }

    public function hash(string $password): string
    {
        return $this->hasher->hash($password);
    }

    public function validate(string $password, string $hash): bool
    {
        return $this->hasher->validate($password, $hash);
    }
}
