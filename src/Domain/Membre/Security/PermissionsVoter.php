<?php


namespace App\Domain\Membre\Security;


use App\Domain\Membre\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PermissionsVoter extends Voter
{
    private $security;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(Security $security,RequestStack $requestStack)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
    }
    protected function supports(string $attribute, $subject)
    {
       if (!$this->security->getUser() instanceof User){
           return false;
       }
       return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User ) {
            return false;
        }
        if ($user->isSuperAdmin()){
            return true;
        }
        $current_route=$this->requestStack->getCurrentRequest()->get('_route');
        if (!$user->hasPermission($current_route)){
            return false;
        }

        return true;
    }
}