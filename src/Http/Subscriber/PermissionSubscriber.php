<?php


namespace App\Http\Subscriber;


use _HumbugBoxaf515cad4e15\Nette\Neon\Exception;
use ApiPlatform\Core\Api\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class PermissionSubscriber implements  EventSubscriberInterface
{
    CONST PERRMESTION_ACCESS="PERRMESTION_ACCESS";
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(Security $security,RequestStack $requestStack,UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
           // KernelEvents::REQUEST => ['beforControllerPermession'],
        ];
    }
    public function beforControllerPermession(RequestEvent $event){

        $current_route=$this->requestStack->getCurrentRequest()->get('_route');
        if ($current_route !="default" && $this->security->isGranted(self::PERRMESTION_ACCESS) ){
             throw new HttpException(400, 'Invalid Permession !');
        }else{
            return;
        }


    }
}