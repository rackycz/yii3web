<?php

declare(strict_types=1);

namespace App\User\Form;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\In;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\RulesProviderInterface;

final class UserUpdateForm extends FormModel implements RulesProviderInterface
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?int $status = null;

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
        $this->name = $data['name'] ?? null;
        $this->surname = $data['surname'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->status = isset($data['status']) ? (int)$data['status'] : null;
    }
}
