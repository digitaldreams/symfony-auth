<?php

namespace App\Persistence\Repository;

use App\Entity\AccessToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function findOneByToken(string $token)
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function save(string $jwt, User $user)
    {
        $token = new AccessToken();
        $token->setToken($jwt);
        $token->setUser($user);

        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();
    }

}
