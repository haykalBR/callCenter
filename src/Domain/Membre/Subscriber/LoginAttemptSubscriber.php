<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Subscriber;

use App\Domain\Membre\Event\BadPasswordLoginEvent;
use App\Domain\Membre\Service\LoginAttemptService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginAttemptSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoginAttemptService
     */
    private $loginAttemptService;

    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService = $loginAttemptService;
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
