<?php


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