<?php

namespace App\Service\User;

use App\Entity\User;
use App\Utils\Validator\UniqueValue;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 191)]
        public string $name,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(max: 191)]
        #[UniqueValue(entity: User::class, field: 'email')]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(max: 180)]
        #[UniqueValue(entity: User::class, field: 'username')]
        public string $username,
        #[Assert\NotBlank]
        #[Assert\Length(['min' => 6])]
        #[Assert\NotCompromisedPassword]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_MEDIUM)]
        public string $password,
    ) {
    }
}