<?php


namespace App\Infrastructure\Data\Subscriber;


use App\Infrastructure\Data\Event\MailExcelEvent;
use App\Infrastructure\Data\Mailer\MailerExcel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaileExcelSubscriber implements EventSubscriberInterface
{

    /**
     * @var MailerExcel
     */
    private MailerExcel $mailerExcel;

    public function __construct(MailerExcel $mailerExcel)
    {
        $this->mailerExcel = $mailerExcel;
    }
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            MailExcelEvent::class            => 'notify',
        ];
    }

    public function notify(MailExcelEvent $event){
            $this->mailerExcel->send($event->getDate());
    }
}