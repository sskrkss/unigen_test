<?php

namespace App\Exception;

class RequestConstraintException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
