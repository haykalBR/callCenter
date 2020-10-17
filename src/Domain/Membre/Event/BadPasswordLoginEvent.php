<?php


namespace App\Domain\Membre\Event;


use App\Domain\Membre\Entity\User;

class BadPasswordLoginEvent
{
    private  $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}