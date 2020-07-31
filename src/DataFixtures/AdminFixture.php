<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixture extends Fixture
{
    protected $passwordEncoder;

    /**
     * UserFixture constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setName('System Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        $user = new User();
        $user->setName('System User');
        $user->setEmail('user@user.com');
        $user->setUsername('user');
        $user->setPassword($this->passwordEncoder->encodePassword($admin, 'user'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}
