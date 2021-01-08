<?php


namespace App\Infrastructure\Data\Exceptions;


class CastingException extends  \Exception
{
    public function __construct(
        string $message = ' Unable to Cast Array To Object',
        array $messageData = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $messageData, $code, $previous);
    }

}