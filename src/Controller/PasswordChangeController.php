<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordChangeController extends AbstractController
{
    /**
     * @Route("/password/change", name="password_change",methods="GET|HEAD")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        return $this->render('password_change/index.html.twig');
    }

    /**
     * @Route("/password_save/save", name="password_save",methods="POST|PUT")
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordEncoder
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface             $validator
     *
     * @param \Doctrine\ORM\EntityManagerInterface                                  $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function change(Request $request, UserPasswordHasherInterface $passwordEncoder, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $input = $request->request->all();
        $constraint = new Assert\Collection([
            'old_password' => new UserPassword(["message" => "Wrong value for your current password"]),
            'new_password' => new Assert\Length(['min' => 6]),
            'confirm_new_password' => new Assert\EqualTo([
                'value' => $request->get('new_password'),
                'message' => 'Confirm password does not match.',
            ]),
        ]);
        $errors = $validator->validate($input, $constraint);

        if (count($errors) > 0) {
            return $this->render('password_change/index.html.twig', [
                'errors' => $errors,
            ]);
        }
        $user = $this->getUser();

        $user->setPassword($passwordEncoder->hashPassword($user, $request->get('new_password')));
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Password changed successfully');
        return $this->redirectToRoute('home');
    }
}
