<?php
namespace App\Tests;

use App\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    /**
     * @test 
     * @return void
     * @throws \Exception
     */
    public function a_logged_in_user_can_see_profile_page(){
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@user.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/password/change');
        $this->assertResponseIsSuccessful();
    }
}