<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Article;


final class ArticleVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE'; 

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
        && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

            /** @var Article $article */    
            $article = $subject;    

            switch ($attribute) {
                case self::EDIT:

                    return $article->getAuthor() === $user;

                case self::DELETE:
                    
                    return ($article->getAuthor() === $user || in_array('ROLE_ADMIN', $user->getRoles()));

            }

        return false;

    }
}
