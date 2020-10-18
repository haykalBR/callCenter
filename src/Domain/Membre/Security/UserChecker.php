<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Security;

use App\Domain\Membre\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Core\Exception\UserBannedException;
use App\Domain\Membre\Service\LoginAttemptService;
use App\Core\Exception\TooManyBadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @var LoginAttemptService
     */
    private $attemptService;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(LoginAttemptService $attemptService, EntityManagerInterface $manager)
    {
        $this->attemptService = $attemptService;
        $this->manager        = $manager;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if (!$user->getEnabled()) {
            throw new UserBannedException();
        }
        if ($user instanceof User && $this->attemptService->limitReachedFor($user)) {
            $user->setEnabled(false);
            $this->manager->flush();
            throw new TooManyBadCredentialsException();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof  User) {
            return;
        }
    }
}
