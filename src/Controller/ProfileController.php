<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserInterface $user)
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/update", name="profile_update")
     * @param \Symfony\Component\HttpFoundation\Request                  $request
     * @param \Doctrine\ORM\EntityManagerInterface                       $entityManager
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface  $validator
     *
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $token = new CsrfToken('authenticate', $request->get('_csrf_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->getUser();
        $user->setName($request->get('name'));
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));

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
