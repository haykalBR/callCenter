<?php


namespace App\Domain\Membre\Subscriber;


use App\Domain\Membre\Event\BadPasswordLoginEvent;
use App\Domain\Membre\Service\LoginAttemptService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginAttemptSubscriber implements EventSubscriberInterface
{
    /**
     * @var $loginAttemptService LoginAttemptService
     */
    private $loginAttemptService;
    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService=$loginAttemptService;
    }
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BadPasswordLoginEvent::class => 'onAuthenticationFailure',
        ];
    }
    public function onAuthenticationFailure(BadPasswordLoginEvent $event): void
    {
        $this->loginAttemptService->addAttempt($event->getUser());
    }




}