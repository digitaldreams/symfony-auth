<?php

namespace App\Tests\Auth;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Persistence\Repository\UserRepository;

class PasswordChangeControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function a_user_can_see_password_change_form()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@user.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/password/change');
        $this->assertResponseIsSuccessful();
        #$this->assertSelectorTextContains('h1', 'Please sign in');
    }
}