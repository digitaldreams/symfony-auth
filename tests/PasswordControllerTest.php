<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordControllerTest extends WebTestCase
{

    /**
     * @test
     */
    public function it_show_forget_password_form(): void
    {
        $client = static::createClient();
        $crawler= $client->request('GET','/reset-password');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Reset your password');
    }
}
