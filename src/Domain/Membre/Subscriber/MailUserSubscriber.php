<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Subscriber;

use App\Domain\Membre\Service\MailerService;
use App\Domain\Membre\Event\MailAddUserEvent;
use App\Domain\Membre\Event\MailRegeneratePasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailUserSubscriber implements EventSubscriberInterface
{
    private MailerService $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MailAddUserEvent::class            => 'sendAddUser',
            MailRegeneratePasswordEvent::class => 'regeneratePassword',
        ];
    }

    public function sendAddUser(MailAddUserEvent $event): void
    {
        $this->mailerService->sendAddUser($event->getUser(), $event->getPassword());
    }

    public function regeneratePassword(MailRegeneratePasswordEvent $event)
    {
        $this->mailerService->changePasswordUser($event->getUser(), $event->getPassword());
    }
}
