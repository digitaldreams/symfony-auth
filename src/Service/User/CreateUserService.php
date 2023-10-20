<?php

namespace App\Service\User;

use App\Entity\User;
use App\Enum\UserRole;
use App\Events\UserRegisteredEvent;
use App\Persistence\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private UserPasswordHasherInterface $passwordEncoder,
        private UserRepository $userRepository
    ) {
    }

    public function execute(CreateUserRequest $request): User
    {
        $user = new User();
        $user->setName($request->name);
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $request->password));
        $user->setRoles([UserRole::USER]);
        $this->userRepository->save($user);

        $this->dispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $user;
    }
}