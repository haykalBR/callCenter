<?php


namespace App\Domain\Membre\Event;

use App\Domain\Membre\Entity\User;

class MailRegeneratePasswordEvent
{
    /**
     * @var User
     */
    private User $user;
    /**
     * @var string
     */
    private string $psssword;

    /**
     * MailUserEvent constructor.
     * @param User $user
     * @param string $psssword
     */
    public function __construct(User $user, string $psssword)
    {
        $this->user = $user;
        $this->psssword = $psssword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->psssword;
    }
}