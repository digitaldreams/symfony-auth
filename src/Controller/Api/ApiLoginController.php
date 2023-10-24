<?php

namespace App\Controller\Api;

use App\Entity\AccessToken;
use App\Persistence\Repository\AccessTokenRepository;
use App\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiLoginController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private AccessTokenRepository $accessTokenRepository,
        private UserPasswordHasherInterface $hasher,
        private ParameterBagInterface $parameterBag
    ) {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $expireAt = new \DateTimeImmutable('+24 hours');
        if ($user && $this->hasher->isPasswordValid($user, $password)) {
            if (is_null($user->getVerifiedAt())) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Your account is not verified yet.',
                ]);
            }

            $payload = [
                'iss' => $this->parameterBag->get('jwt.iss'),
                'sub' => $user->getUid(),
                'iat' => (new \DateTimeImmutable())->getTimestamp(),
                'exp' => $expireAt->getTimestamp()
            ];
            $jwtToken = JWT::encode(
                $payload,
                $this->parameterBag->get('jwt.secret'),
                $this->parameterBag->get('jwt.algorithm')
            );
            $this->accessTokenRepository->save($jwtToken, $user);

            return $this->json([
                'status' => 'success',
                'user_id' => $user->getUid(),
                "token" => $jwtToken,
                'expire_at' => $expireAt->format('c')
            ]);
        } else {
            return $this->json(['error' => 'User or password is not match']);
        }
    }

    #[Route('/api/login-form', 'api_login_form', methods: ['GET', 'HEAD'])]
    public function form()
    {
        return $this->render('login/api-login.html.twig');
    }
}