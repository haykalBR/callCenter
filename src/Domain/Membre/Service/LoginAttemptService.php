<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Service;

use App\Domain\Membre\Entity\LoginAttempt;
use App\Domain\Membre\Entity\User;
use App\Domain\Membre\Repository\LoginAttemptRepository;
use Doctrine\ORM\EntityManagerInterface;

class LoginAttemptService
{
    const ATTEMPTS = 3;

    private $repository;
    private $em;

    public function __construct(
        LoginAttemptRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function addAttempt(User $user): void
    {
        // TODO : Envoyer un email au bout du Xème essai
        $attempt = (new LoginAttempt())->setUser($user);
        $this->em->persist($attempt);
        $this->em->flush();
    }

    public function limitReachedFor(User $user): bool
    {
        return $this->repository->countRecentFor($user, 30) >= self::ATTEMPTS;
    }
}
