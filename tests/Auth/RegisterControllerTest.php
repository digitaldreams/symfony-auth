<?php

namespace App\Tests\Auth;

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
        $crawler = $this->client->request('GET', '/auth/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    /**
     * @test
     */
    public function a_guest_can_register(): void
    {
        $crawler = $this->client->request('GET', '/auth/register');
        $id = uniqid();
        $crawler = $this->client->submitForm('register', [
            'name' => 'Demo user ' . $id,
            'email' => 'demo' . $id . '@test.com',
            'username' => 'demo' . $id,
            'password' => uniqid("I_@#",true),
        ]);
        $this->assertResponseRedirects('/auth/login');

    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
