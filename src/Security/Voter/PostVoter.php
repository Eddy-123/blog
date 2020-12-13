<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Post;

class PostVoter extends Voter
{
    public const EDIT = "EDIT";
    
    public function supports($attribute, $subject)
    {
        if(!$subject instanceof Post)
        {
            return false;
        }
        
        if(!in_array($attribute, [self::EDIT]))
        {
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @param string $attribute
     * @param Post $subject
     * @param TokenInterface $token
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token) {
        /** @var User */
        $user = $token->getUser();
        
        switch ($attribute)
        {
            case self::EDIT :
                return $user === $subject->getUser();
                break;
        }
    }
}
