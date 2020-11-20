<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Mercure\Subscriber;

use App\Domain\Membre\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use App\Infrastructure\Mercure\Service\CookieGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MercureCookieMiddleware implements EventSubscriberInterface
{
    private Security $security;
    private CookieGenerator $generator;

    public function __construct(CookieGenerator $generator, Security $security)
    {
        $this->security  = $security;
        $this->generator = $generator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['setMercureCookie'],
        ];
    }

    public function setMercureCookie(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request  = $event->getRequest();
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType() ||
            !\in_array('text/html', $request->getAcceptableContentTypes(), true) ||
            !($user = $this->security->getUser()) instanceof User
        ) {
            return;
        }
        $response->headers->setCookie($this->generator->generate());
    }
}
