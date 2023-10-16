<?php

namespace App\Service\Password;

use App\Persistence\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordChangeService
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordEncoder,
    ) {
    }

    public function execute(PasswordChangeRequest $request)
    {
        $user = $this->security->getUser();
        $hash = $this->passwordEncoder->hashPassword($user, $request->newPassword);
        $this->userRepository->upgradePassword($user, $hash);
    }
}