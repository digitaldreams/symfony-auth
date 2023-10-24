<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/api/user')]
    public function index(#[CurrentUser] User $user)
    {
        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername()
        ]);
    }
}