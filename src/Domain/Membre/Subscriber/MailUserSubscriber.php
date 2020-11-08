<?php


namespace App\Domain\Membre\Subscriber;

use App\Domain\Membre\Event\MailAddUserEvent;
use App\Domain\Membre\Event\MailRegeneratePasswordEvent;
use App\Domain\Membre\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerService
     */
    private MailerService $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() :array
    {
        return [
            MailAddUserEvent::class => 'sendAddUser',
            MailRegeneratePasswordEvent::class => 'regeneratePassword',
        ];
    }

    /**
     * @param MailAddUserEvent $event
     */
    public function sendAddUser(MailAddUserEvent $event) :void {
        $this->mailerService->sendAddUser($event->getUser(),$event->getPassword());
    }
    /**
     * @param MailRegeneratePasswordEvent $event
     */
    public function regeneratePassword(MailRegeneratePasswordEvent $event){
        $this->mailerService->changePasswordUser($event->getUser(),$event->getPassword());
    }
}