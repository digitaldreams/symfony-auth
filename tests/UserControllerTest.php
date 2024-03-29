<?php

namespace App\Tests;

use App\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @test
     * @return void
     */
    public function a_logged_in_admin_can_see_users_page()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@admin.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/users');
        $this->assertResponseIsSuccessful();
    }
}
