<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Exception;

 use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

 final class TooManyBadCredentialsException extends CustomUserMessageAuthenticationException
 {
     public function __construct(
         string $message = 'Le compte a été verrouillé suite à de trop nombreuses tentatives de connexion',
         array $messageData = [],
         int $code = 0,
         \Throwable $previous = null
     ) {
         parent::__construct($message, $messageData, $code, $previous);
     }
 }
