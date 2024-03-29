<?php

namespace App\Security;

use App\Persistence\Repository\AccessTokenRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $repository,
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        try {
            $decoded = JWT::decode(
                $accessToken,
                new Key($this->parameterBag->get('jwt.secret'), $this->parameterBag->get('jwt.algorithm'))
            );

            if ((new \DateTimeImmutable())->setTimestamp($decoded->exp) < new \DateTimeImmutable()) {
                throw new CustomUserMessageAuthenticationException('Token Expired');
            }

            // return $decoded;
            $accessToken = $this->repository->findOneByToken($accessToken);
            if (null === $accessToken || !$accessToken->isValid()) {
                throw new BadCredentialsException('Invalid credentials.');
            }
            // and return a UserBadge object containing the user identifier from the found token
            return new UserBadge($accessToken->getUser()?->getUsername());
        } catch (\UnexpectedValueException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid Access Token');
        } catch (\Exception $e) {
            throw new BadCredentialsException($e->getMessage());
        }
    }
}