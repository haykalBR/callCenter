<?php


namespace App\Domain\Membre\Security;


use App\Core\Exception\TooManyBadCredentialsException;
use App\Core\Exception\UserBannedException;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Service\LoginAttemptService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function __construct(LoginAttemptService $attemptService ,EntityManagerInterface $manager)
    {
        $this->attemptService = $attemptService;
        $this->manager = $manager;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if (!$user->getEnabled()){
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