<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Membre\Repository\ResetPasswordRequestRepository;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct(User $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }
}
