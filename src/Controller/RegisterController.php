<?php

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register_form",methods="GET|HEAD")
     */
    public function index()
    {
        return $this->render('register/index.html.twig', [
            'errors' => [],
            'user' => new User(),
        ]);
    }

    /**
     * @Route("/register/store", name="register",methods="POST")
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface             $validator
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @param \Doctrine\ORM\EntityManagerInterface                                  $entityManager
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface           $dispatcher
     *
     * @return string
     */
    public function store(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $user = new User();
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setUsername($request->get('username'));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->render('register/index.html.twig',
                [
                    'errors' => $errors,
                    'user' => $user,
                ]
            );
        }

        $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')));
        $entityManager->persist($user);
        $entityManager->flush();

        $dispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $this->redirectToRoute('app_login');
    }
}
