<?php

namespace App\Persistence\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordEncoder;

    /**
     * UserFixture constructor.
     *
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName('System Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();

        $user = new User();
        $user->setName('System User');
        $user->setEmail('user@user.com');
        $user->setUsername('user');
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'user'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}
