<?php

namespace App\Service\User;

use App\Entity\User;
use App\Persistence\Repository\UserRepository;
use App\Service\ImageUploadService;
use Symfony\Bundle\SecurityBundle\Security;

class UpdateProfileService
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private ImageUploadService $imageUploadService
    ) {
    }

    public function execute(UpdateProfileRequest $request): User
    {
        $user = $this->security->getUser();
        $user->setName($request->name);
        $user->setUsername($request->username);
        $user->setEmail($request->email);
        if($request->avatar){
            $user->setAvatar($this->imageUploadService->upload($request->avatar));
        }

        return $this->userRepository->save($user);
    }
}