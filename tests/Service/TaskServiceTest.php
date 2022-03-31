<?php

namespace App\Tests\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Test\TestCase;
use Psr\Log\LoggerInterface;

class TaskServiceTest extends TestCase
{

    private $userTest;
    private $taskTest;

    protected function setUp(): void
    {
        $this->userTest = new User();
        $this->userTest->setUsername('usernameTest');

        $this->taskTest = new Task();
        $this->taskTest->setTitle('TitleTest');
        $this->taskTest->setContent('ContentTest');
    }

    public function testCreateTask(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();

        $createdTask = $taskService->createTask();

        $this->assertEquals($task->getTitle(), $createdTask->getTitle());
        $this->assertEquals($task->getContent(), $createdTask->getContent());
    }

    public function testGetTasksTodoList(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $taskRepository = $this->getMockBuilder(TaskRepository::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getRepository')->willReturn($taskRepository);

        $taskRepository->expects($this->once())->method('findTasksTodo');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);


        $tasks = $taskService->getTasksTodoList();

        $this->assertIsArray($tasks);
    }

    public function testGetTasksDoneList(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $taskRepository = $this->getMockBuilder(TaskRepository::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getRepository')->willReturn($taskRepository);

        $taskRepository->expects($this->once())->method('findTasksDone');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);


        $tasks = $taskService->getTasksDoneList();

        $this->assertIsArray($tasks);
    }

    public function testAddTaskWithAuthenticatedUser(): void
    {
        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInterface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $user = new User();
        $user->setUsername('usernameTest');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();

        $task->setTitle('TitleTest');
        $task->setContent('ContentTest');

        $taskService->addTask($task, $user);

        $this->assertSame($user, $task->getUser());

    }

    public function testAddTaskWithoutAuthenticatedUser(): void
    {
        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInterface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $logger->expects($this->once())->method('critical')->will($this->returnSelf());

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();

        $user = null;

        $taskService->addTask($task, $user);

    }

    public function testUpdateTaskWithAuthenticatedUser(): void
    {
                /** @return MockInterface|ManagerRegistry */
                $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

                /** @return MockInterface|LoggerInterface */
                $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        
                $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        
                $managerRegistry->method('getManager')->willReturn($entityManager);
        
                /**
                 * @var MockObject|ManagerRegistry $managerRegistry
                 * @var MockObject|LoggerInterface $logger
                 */
                $taskService = new TaskService($managerRegistry, $logger);

                $entityManager->expects($this->once())->method('flush');

                $taskService->updateTask($this->userTest);
    }

    public function testUpdateTaskWithoutAuthenticatedUser(): void
    {
                /** @return MockInterface|ManagerRegistry */
                $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

                /** @return MockInterface|LoggerInterface */
                $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        
                $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        
                $managerRegistry->method('getManager')->willReturn($entityManager);

                $logger->expects($this->once())->method('critical')->will($this->returnSelf());
        
                /**
                 * @var MockObject|ManagerRegistry $managerRegistry
                 * @var MockObject|LoggerInterface $logger
                 */
                $taskService = new TaskService($managerRegistry, $logger);

                $taskService->updateTask(null);
    }


    public function testUpdateTaskStatusAuthenticatedUser(): void
    {
                /** @return MockInterface|ManagerRegistry */
                $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

                /** @return MockInterface|LoggerInterface */
                $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        
                $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        
                $managerRegistry->method('getManager')->willReturn($entityManager);
        
                /**
                 * @var MockObject|ManagerRegistry $managerRegistry
                 * @var MockObject|LoggerInterface $logger
                 */
                $taskService = new TaskService($managerRegistry, $logger);

                $entityManager->expects($this->once())->method('flush');

                $taskService->toggleTask($this->taskTest, $this->userTest);
    }

    public function testUpdateTaskStatusGuest(): void
    {
                /** @return MockInterface|ManagerRegistry */
                $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

                /** @return MockInterface|LoggerInterface */
                $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        
                $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        
                $managerRegistry->method('getManager')->willReturn($entityManager);

                $logger->expects($this->once())->method('critical')->will($this->returnSelf());
        
                /**
                 * @var MockObject|ManagerRegistry $managerRegistry
                 * @var MockObject|LoggerInterface $logger
                 */
                $taskService = new TaskService($managerRegistry, $logger);

                $taskService->toggleTask($this->taskTest, null);
    }

    public function testAuthorDeleteIsOwnTask(): void
    {

        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInteface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();
        $task->setUser($this->userTest);

        $entityManager->expects($this->once())->method('flush');

        $taskService->deleteTask($task, $this->userTest);
    }

    public function testNoUserDeleteTask(): void
    {

        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInterface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $logger->expects($this->once())->method('critical')->will($this->returnSelf());

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();

        $taskService->deleteTask($task, null);
    }

    public function testUserDeleteOtherTask(): void
    {

        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInterface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $logger->expects($this->once())->method('critical')->will($this->returnSelf());

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();
        $task->setUser(new User());

        $taskService->deleteTask($task, $this->userTest);
    }

    public function testUserDeleteAnonymousTask(): void
    {

        /** @return MockInterface|ManagerRegistry */
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();

        /** @return MockInterface|LoggerInterface */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $logger->expects($this->once())->method('critical')->will($this->returnSelf());

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|LoggerInterface $logger
         */
        $taskService = new TaskService($managerRegistry, $logger);

        $task = new Task();

        $taskService->deleteTask($task, $this->userTest);
    }
}