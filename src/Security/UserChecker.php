<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if (is_null($user->getVerifiedAt())) {
            throw new CustomUserMessageAccountStatusException('Your account is not verified yet.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        //  if (!$user->isActive())) {
        // throw new CustomUserMessageAccountStatusException('Your user account is $status.');
        //  }
    }
}