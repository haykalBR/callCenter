<?php


namespace App\Domain\Membre\Subscriber;


use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Service\UserPermissionsService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Gedmo\Loggable\Entity\LogEntry;
use Symfony\Component\HttpFoundation\RequestStack;

class UserPermissionsSubscriber implements EventSubscriber
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var UserPermissionsService
     */
    private UserPermissionsService $userPermissionsService;

    public function __construct(RequestStack $requestStack,UserPermissionsService $userPermissionsService)
    {
        $this->requestStack = $requestStack;
        $this->userPermissionsService = $userPermissionsService;
    }

    public function getSubscribedEvents(): array
    {
        return [
         /*   Events::postPersist,
            Events::postUpdate,*/
        ];
    }
    public function postPersist(LifecycleEventArgs $args){
        /**
         * TODO add condtion from any action Important !!!
         */
        if ($args->getObject() instanceof User){
            $request=$this->requestStack->getCurrentRequest();
           $this->userPermissionsService->CreateUserPermissions($args->getObject(),$request->request->all()['user']);
        }
    }
    public function postUpdate(LifecycleEventArgs $args){
        if ($args->getObject() instanceof User){
            $request=$this->requestStack->getCurrentRequest();
            $this->userPermissionsService->removeUserPermissions($args->getObject());
            $this->userPermissionsService->CreateUserPermissions($args->getObject(),$request->request->all()['user']);
        }
    }

}