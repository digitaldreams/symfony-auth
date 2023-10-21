<?php

namespace App\Tests\Auth;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordResetControllerTest extends WebTestCase
{

    /**
     * @test
     */
    public function a_guest_can_see_forget_password_form(): void
    {
        $client = static::createClient();
        $crawler= $client->request('GET','/auth/password/forget');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Reset your password');
    }
}
