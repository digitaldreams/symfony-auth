<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function a_guest_can_see_homepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}