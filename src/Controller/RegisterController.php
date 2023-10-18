<?php

namespace App\Controller;

use App\Attribute\FillDto;
use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Service\User\CreateUserRequest;
use App\Service\User\CreateUserService;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{

    public function __construct(private CreateUserService $userService)
    {
    }

    #[Route("/auth/register", name: "register_form", methods: ["GET", "HEAD"])]
    public function index()
    {
        return $this->render('register/index.html.twig', [
            'errors' => [],
            'user' => new User(),
        ]);
    }


    #[Route("/auth/register/store", name: "register", methods: "POST")]
    #[Template('register/index.html.twig')]
    public function store(
        #[FillDto] CreateUserRequest $createUserRequest,
        Request $request,
        ValidatorInterface $validator,
    ) {
        if ($request->getMethod() === 'POST') {
            $user = new User();

            $errors = $validator->validate($createUserRequest);
            if (count($errors) === 0) {
                $user = $this->userService->execute($createUserRequest);
                $this->addFlash('message', 'Registration completed successfully');
                return $this->redirectToRoute('app_login');
            }
        }
        return [
            'errors' => $errors,
            'user' => new User()
        ];
    }
}
