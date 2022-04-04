<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $task = new Task();
        $task->setTitle('Task');
        $task->setContent('Content of task');

        $manager->persist($task);
    
        $task2 = new Task();
        $task2->setTitle('Task 2');
        $task2->setContent('Content of task 2');
        $task2->setUser($this->getReference(UserFixtures::USER));
        $manager->persist($task2);
        
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
