<?php

declare(strict_types=1);

namespace App\User\Form;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\In;
use Yiisoft\Validator\Rule\InRange;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\RulesProviderInterface;

final class UserUpdateForm extends FormModel implements RulesProviderInterface
{
    private ?string $name = null;
    private ?string $surname = null;
    private ?string $username = null;
    private ?string $email = null;
    private ?string $phone = null;
    private ?int $status = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
            'surname' => 'Surname',
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
        ];
    }

    public function getAttributeHints(): array
    {
        return [
            'name' => 'Enter the user\'s first name.',
            'surname' => 'Enter the user\'s last name.',
            'username' => 'Optional login username.',
            'email' => 'Enter a valid email address.',
            'phone' => 'Optional phone number.',
            'status' => 'Select the user status.',
        ];
    }

    public function getRules(): array
    {
        return [
            'name' => [
                new Required(),
                new Length(min: 1, max: 255),
            ],
            'surname' => [
                new Required(),
                new Length(min: 1, max: 255),
            ],
            'username' => [
                new Length(max: 255),
            ],
            'email' => [
                new Required(),
                new Email(),
                new Length(max: 255),
            ],
            'phone' => [
                new Length(max: 255),
            ],
            'status' => [
                new Required(),
                new In([0, 100]),
            ],
        ];
    }

    public function populate(array $data): void
    {
        $this->setName($data['name'] ?? null);
        $this->setSurname($data['surname'] ?? null);
        $this->setUsername($data['username'] ?? null);
        $this->setEmail($data['email'] ?? null);
        $this->setPhone($data['phone'] ?? null);

        $this->setStatus(
            isset($data['status']) ? (int)$data['status'] : null
        );
    }
}
