<?php

namespace App\tests\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $userRepo;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['HTTP_HOST' => 'todolist.local']);
        $this->userRepo = static::getContainer()->get(ManagerRegistry::class)->getRepository(User::class);
    }

    public function testTaskListIsUpForUser(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testTaskListCount(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks');

        // TaskFixtures create 2 tasks
        // Todo faire plus general??
        $this->assertEquals(2,  $crawler->filter('.thumbnail')->count());
    }

    public function testUserCanDeleteOwnTask(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks');
        // TaskFixtures create 1 task with user
        $this->assertEquals(1,  $crawler->filter('button:contains("Supprimer")')->count());
    }

    public function testTaskDoneListIsUpForUser(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks/done');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateActionIsUp(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateActionSubmitValidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form();

        $form['task[title]'] = 'New task';
        $form['task[content]'] = 'New task content';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }

    public function testCreateActionSubmitInvalidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks/create');

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form();

        $form['task[title]'] = '';
        $form['task[content]'] = 'New task content';

        $this->client->submit($form);

        $this->assertSelectorTextContains('li', 'Vous devez saisir un titre.');
    }

    public function testEditActionIsUp(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks/2/edit');

        $this->assertResponseIsSuccessful();
    }

    public function testEditActionSubmitValidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks/2/edit');

        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        $form = $buttonCrawlerNode->form();

        $form['task[title]'] = 'Edit task 2';
        $form['task[content]'] = 'New task 2 content';

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }

    public function testEditActionSubmitInvalidForm(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $crawler = $this->client->request('GET', '/tasks/2/edit');

        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        $form = $buttonCrawlerNode->form();

        $form['task[title]'] = '';
        $form['task[content]'] = 'New task 2 content';

        $this->client->submit($form);

        $this->assertSelectorTextContains('li', 'Vous devez saisir un titre.');
    }

    public function testToggleTaskActionOnOff(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks/2/toggle');

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');
        
        $this->client->request('GET', '/tasks/2/toggle');

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }

    public function testDeleteTaskActionWithNoAuthorFromUserRole(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'user']));

        $this->client->request('GET', '/tasks/1/delete');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskActionWithNoAuthorFromAdminRole(): void
    {
        $this->client->loginUser($this->userRepo->findOneBy(['username' => 'admin']));

        $this->client->request('GET', '/tasks/1/delete');

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('strong', 'Superbe !');
    }
}
