<?php
/**
 * User Voter
 * 
 * Permission manager for user
 */
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    const MANAGE = 'manage';
    const REGISTER = 'register';
    const REGISTER_ROLES = 'register_roles';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::MANAGE, self::REGISTER, self::REGISTER_ROLES])) {
            return false;
        }

        if(!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();

        switch($attribute) {
            case self::MANAGE: 
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
            case self::REGISTER:
                if($user === null || $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
            case self::REGISTER_ROLES:
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
        }

        throw new \LogicException('This code should not be reached!');

    }
}