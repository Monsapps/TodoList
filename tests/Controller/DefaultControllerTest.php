<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    private $client;
    private $userRepo;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepo = static::getContainer()->get(ManagerRegistry::class)->getRepository(User::class);
    }

    public function testIndexIsUpWithoutAuthenticatedUser()
    {

        $this->client->request('GET', '/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexIsUpWithAuthenticatedUser(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }
}
