<?php

namespace App\Controller;

use App\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route("/app/users", name:"user")]
    public function index(Request $request, UserRepository $userRepository)
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
