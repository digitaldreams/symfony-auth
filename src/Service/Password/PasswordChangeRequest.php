<?php

namespace App\Service\Password;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

readonly class PasswordChangeRequest
{
    public function __construct(
        #[SecurityAssert\UserPassword(message: "Wrong value for your current password")]
        public string $oldPassword,
        #[Assert\Length(['min' => 6])]
        #[Assert\NotCompromisedPassword]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_VERY_STRONG)]
        public string $newPassword,
        public string $confirmNewPassword
    ) {
    }
}