<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Event;

use App\Domain\Membre\Entity\User;

class MailAddUserEvent
{
    private User $user;

    private string $psssword;

    /**
     * MailUserEvent constructor.
     */
    public function __construct(User $user, string $psssword)
    {
        $this->user     = $user;
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
