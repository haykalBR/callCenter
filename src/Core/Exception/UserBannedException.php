<?php


namespace App\Core\Exception;


use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

 final class UserBannedException extends CustomUserMessageAuthenticationException
{
     public function __construct($message = 'Ce compte a été bloqué', array $messageData = [], $code = 0, Throwable $previous = null)
     {
         parent::__construct($message, $messageData, $code, $previous);
     }

}