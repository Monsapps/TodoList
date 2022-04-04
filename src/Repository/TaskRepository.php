<?php
/**
 * TaskRepository
 * 
 * Quering tasks table
 */
namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $manager)
    {
        parent::__construct($manager, Task::class);
    }

    /**
     * Get tasks todo list
     * 
     * @return Task[]
     */
    public function findTasksTodo(): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.isDone = 0');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Get tasks done list
     * 
     * @return Task[]
     */
    public function findTasksDone(): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.isDone = 1');

        return $queryBuilder->getQuery()->getResult();
    }
}
