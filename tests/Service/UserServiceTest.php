<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase
{
    public function testGetUserList(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();

        $objectRepo = $this->getMockBuilder(ObjectRepository::class)->getMock();

        $managerRegistry->method('getRepository')->willReturn($objectRepo);

        $managerRegistry->expects($this->once())->method('getRepository')->with(User::class);

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|UserPasswordHasherInterface $passwordHasher
         */
        $userService = new UserService($managerRegistry, $passwordHasher);

        $userService->getUsersList();
    }

    public function testCreateUser(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|UserPasswordHasherInterface $passwordHasher
         */
        $userService = new UserService($managerRegistry, $passwordHasher);

        $expected = new User();

        $this->assertEquals($expected, $userService->createUser());
    }

    public function testAddUser(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $entityManager->expects($this->once())->method('persist');

        $entityManager->expects($this->once())->method('flush');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|UserPasswordHasherInterface $passwordHasher
         */
        $userService = new UserService($managerRegistry, $passwordHasher);

        $user = new User();
        $user->setUsername('usernameTest');
        $user->setEmail('user@test.com');
        $user->setPassword('pass_12345');

        $userService->addUser($user);
    }

    public function testUpdateUserWithoutPass(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $passwordHasher->expects($this->never())->method('hashPassword');

        $entityManager->expects($this->once())->method('flush');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|UserPasswordHasherInterface $passwordHasher
         */
        $userService = new UserService($managerRegistry, $passwordHasher);

        $user = new User();
        $user->setUsername('usernameTest');
        $user->setEmail('user@test.com');

        $userService->updateUser($user, null);
    }

    public function testUpdateUserWithPass(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $passwordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->disableOriginalConstructor()->getMock();

        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $managerRegistry->method('getManager')->willReturn($entityManager);

        $passwordHasher->expects($this->once())->method('hashPassword');

        $entityManager->expects($this->once())->method('flush');

        /**
         * @var MockObject|ManagerRegistry $managerRegistry
         * @var MockObject|UserPasswordHasherInterface $passwordHasher
         */
        $userService = new UserService($managerRegistry, $passwordHasher);

        $user = new User();
        $user->setUsername('usernameTest');
        $user->setEmail('user@test.com');

        $userService->updateUser($user, 'test1234');
    }
}