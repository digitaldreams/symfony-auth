<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Service\User\CreateUserRequest;
use App\Service\User\CreateUserService;
use App\Utils\Attribute\FillDto;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{

    public function __construct(private CreateUserService $userService, private TranslatorInterface $translator)
    {
    }

    #[Route("/auth/register", name: "register", methods: ["POST", "GET"])]
    #[Template('register/index.html.twig')]
    public function store(
        #[FillDto] CreateUserRequest $createUserRequest,
        Request $request,
        ValidatorInterface $validator,
    ) {
        $user = new User();

        if ($request->getMethod() === 'POST') {
            $errors = $validator->validate($createUserRequest);
            if (count($errors) === 0) {
                $user = $this->userService->execute($createUserRequest);
                $this->addFlash('message', $this->translator->trans('user.registration_successful'));
                return $this->redirectToRoute('app_login');
            }
        }
        return [
            'errors' => $errors ?? [],
            'user' => $user
        ];
    }
}
