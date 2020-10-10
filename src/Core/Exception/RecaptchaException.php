<?php


namespace App\Core\Exception;


use Symfony\Component\Security\Core\Exception\AuthenticationException;

class RecaptchaException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Please prove you are not a robot.';
    }

}