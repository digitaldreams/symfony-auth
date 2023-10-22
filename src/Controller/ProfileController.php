<?php

namespace App\Controller;

use App\Service\User\UpdateProfileRequest;
use App\Service\User\UpdateProfileService;
use App\Utils\Attribute\FillDto;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
{
    public function __construct(private UpdateProfileService $profileService, private TranslatorInterface $translator)
    {
    }

    #[Route("/app/profile", name: "profile_show", methods: ['GET', 'POST'])]
    #[Template('profile/index.html.twig')]
    public function update(
        Request $request,
        ValidatorInterface $validator,
        CsrfTokenManagerInterface $csrfTokenManager,
        #[CurrentUser] UserInterface $user,
        #[FillDto] UpdateProfileRequest $updateProfileRequest,
    ) {
        $errors = [];
        if ($request->getMethod() === 'POST') {
            $fileErrors = [];
            $token = new CsrfToken('authenticate', $request->get('_csrf_token'));
            if (!$csrfTokenManager->isTokenValid($token)) {
                throw new InvalidCsrfTokenException();
            }

            $errors = $validator->validate($updateProfileRequest);
            if (count($errors) === 0) {
                $this->profileService->execute($updateProfileRequest);
                $this->addFlash('message', $this->translator->trans('user.profile_updated'));
            }
        }

        return [
            'errors' => $errors,
            'user' => $user
        ];
    }
}
