<?php

namespace App\Controller;

use App\Service\Password\PasswordChangeRequest;
use App\Service\Password\PasswordChangeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Attribute\FillDto;

class PasswordChangeController extends AbstractController
{
    public function __construct(private PasswordChangeService $passwordChangeService)
    {
    }

    #[Route("/app/password/change", name: "password_change", methods: ['GET', 'POST'])]
    #[Template('password_change/index.html.twig')]
    public function change(
        #[FillDto] PasswordChangeRequest $passwordChangeRequest,
        Request $request,
        ValidatorInterface $validator
    ) {
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $errors = $validator->validate($passwordChangeRequest);
            if (count($errors) == 0) {
                $this->passwordChangeService->execute($passwordChangeRequest);
                $this->addFlash('message', 'Password changed successfully');
                return $this->redirectToRoute('home');
            }
        }

        return ['errors' => $errors];
    }
}
