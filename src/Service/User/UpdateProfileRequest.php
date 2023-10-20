<?php

namespace App\Service\User;

use App\Entity\User;
use App\Utils\Validator\UniqueValue;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateProfileRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 191)]
        public string $name,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(max: 191)]
        #[UniqueValue(entity: User::class, field: 'email', repositoryMethod: 'validate', currentUser: true)]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(max: 180)]
        #[UniqueValue(entity: User::class, field: 'username', repositoryMethod: 'validate', currentUser: true)]
        public string $username,
        #[Assert\Image(minWidth: 40, maxWidth: 400, minHeight: 40, maxHeight: 400)]
        public ?File $avatar = null
    ) {
    }
}