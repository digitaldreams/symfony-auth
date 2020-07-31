<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_show_login_form(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }

    /**
     * @test
     */
    public function a_user_can_successfully_login(): void
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $crawler = $client->submitForm('submit', [
            'username' => 'user',
            'password' => 'user',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Your Dashboard');
    }

    /**
     * @test
     */
    public function a_user_cannot_login_in_with_wrong_credentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $crawler = $client->submitForm('submit', [
            'username' => 'someOtherUser',
            'password' => 'someOtherPassword',
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }


}
