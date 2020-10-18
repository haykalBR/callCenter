<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Exception;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

 final class UserBannedException extends CustomUserMessageAuthenticationException
 {
     public function __construct($message = 'Ce compte a été bloqué', array $messageData = [], $code = 0, \Throwable $previous = null)
     {
         parent::__construct($message, $messageData, $code, $previous);
     }
 }
