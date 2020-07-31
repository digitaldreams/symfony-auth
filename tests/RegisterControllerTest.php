<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var  \Symfony\Bundle\FrameworkBundle\KernelBrowser;
     */
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @test
     */
    public function it_show_register_form(): void
    {
        $crawler = $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    /**
     * @test
     */
    public function a_guest_can_register(): void
    {
        $crawler = $this->client->request('GET', '/register');
        $id = uniqid();
        $crawler = $this->client->submitForm('register', [
            'name' => 'Demo user ' . $id,
            'email' => 'demo' . $id . '@test.com',
            'username' => 'demo' . $id,
            'password' => '123456',
        ]);
        $this->assertResponseRedirects('/login');

    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
