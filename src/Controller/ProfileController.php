<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController
{
    #[Route("/app/profile", name: "profile_show")]
    public function index(UserInterface $user)
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route("/app/profile/update", name: "profile_update")]
    #[Template('profile/index.html.twig')]
    public function store(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        CsrfTokenManagerInterface $csrfTokenManager,
        SluggerInterface $slugger
    ) {
        $fileErrors = [];
        $token = new CsrfToken('authenticate', $request->get('_csrf_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->getUser();
        $user->setName($request->get('name'));
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));


        if ($request->files->has('avatar')) {
            $avatarFile = $request->files->get('avatar');

            $constraint = new Assert\Collection([
                'avatar' => new Assert\Image([
                    'maxSize' => 2048,
                ]),
            ]);
            $fileErrors = $validator->validate(['avatar' => $request->files->has('avatar')]);
            if (count($fileErrors) > 0) {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                    'errors' => $fileErrors,
                ]);
            }
            $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

            // Move the file to the directory where avatar are stored
            try {
                $avatarFile->move(
                    'images',
                    $newFilename
                );
                $user->setAvatar('/images/' . $newFilename);
            } catch (FileException $e) {
                $this->addFlash('message', $e->getMessage());
                return $this->redirectToRoute('profile');
            }
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'errors' => $errors,
            ]);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
