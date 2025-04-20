<?php

namespace App\Persistence\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Add your fixtures here
        // Example:
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
