<?php
/**
 * TaskVoter
 * 
 * Permission manager for Task
 */

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{

    const VIEW = 'view';
    const ADD = 'add';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const READ = 'read';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::VIEW, self::ADD, self::EDIT, self::DELETE, self::READ])) {
            return false;
        }

        if(!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch($attribute) {
            case self::VIEW: 
            case self::ADD:
            case self::EDIT:
            case self::READ:
                if ($this->security->isGranted('ROLE_USER')) {
                    return true;
                }
            case self::DELETE:
                return $this->canDelete($subject, $user);
            
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $user): bool
    {
        if($task->getUser() == null) {
            // Only admin can delete anonymous task
            if($this->security->isGranted('ROLE_ADMIN')) {
                return true;
            }

        } elseif($task->getUser() == $user) {
            // Only owner can delete task
            return true;
        }

        return false;
    }
}