<?php

namespace App\Exceptions;

class StorageException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Storage error';
        }

        parent::__construct($message, $code, $previous);
    }
}
