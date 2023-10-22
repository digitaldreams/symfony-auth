<?php

namespace App\Controller\Auth;

use App\Service\Password\PasswordChangeRequest;
use App\Service\Password\PasswordChangeService;
use App\Utils\Attribute\FillDto;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordChangeController extends AbstractController
{
    public function __construct(
        private PasswordChangeService $passwordChangeService,
        private TranslatorInterface $translator
    ) {
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
                $this->addFlash('message', $this->translator->trans('password.changed_successfully'));
                return $this->redirectToRoute('home');
            }
        }

        return ['errors' => $errors];
    }
}
