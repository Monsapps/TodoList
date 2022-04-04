<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $userRepo;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['HTTP_HOST' => 'todolist.local']);
        $this->userRepo = static::getContainer()->get(ManagerRegistry::class)->getRepository(User::class);
    }

    public function testListActionIsDownForUserRole(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/users');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testListActionIsDownForAdminRole(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $this->client->request('GET', '/users');

        $this->client->followRedirect(); //Pourquoi ???

        $this->assertResponseIsSuccessful();
    }

    public function testCreateActionIsUpForAll(): void
    {
        $this->client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateActionGuestSubmitValidForm(): void
    {     
        $crawler = $this->client->request('GET', '/users/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form();

        $form['user[username]'] = 'NewUserTest';
        $form['user[password][first]'] = 'pass_12345';
        $form['user[password][second]'] = 'pass_12345';
        $form['user[email]'] = 'email-test@test.com';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');

    }

    public function testCreateActionAdminSubmitValidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $crawler = $this->client->request('GET', '/users/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form();

        $form['user[username]'] = 'NewUserTest';
        $form['user[password][first]'] = 'pass_12345';
        $form['user[password][second]'] = 'pass_12345';
        $form['user[email]'] = 'email-test@test.com';
        $form['user[roles]'] = 'ROLE_USER';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');

    }

    public function testEditActionIsUp(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $this->client->request('GET', '/users/2/edit');

        $this->assertResponseIsSuccessful();
    }

    public function testEditActionSubmitValidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $crawler = $this->client->request('GET', '/users/2/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'Edituser';
        $form['user[password][first]'] = '';
        $form['user[password][second]'] = '';
        $form['user[email]'] = 'email-test133@test.com';
        $form['user[roles]'] = 'ROLE_USER';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }

    public function testEditActionSubmitMissingPasswordInput(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $crawler = $this->client->request('GET', '/users/2/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'Edituser';
        $form['user[email]'] = 'email-test133@test.com';
        $form['user[roles]'] = 'ROLE_USER';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }
}
