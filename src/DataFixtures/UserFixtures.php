<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public const USER = 'user';

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // create an admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@todolist.local');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'pass_1234'));

        $manager->persist($admin);

        // new user
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user-test@todolist.local');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'pass_1234'));

        $this->addReference(self::USER, $user);

        $manager->persist($user);

        $manager->flush();

    }
}