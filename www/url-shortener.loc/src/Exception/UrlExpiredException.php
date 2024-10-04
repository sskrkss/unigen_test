<?php

namespace App\Exception;

class UrlExpiredException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Срок действия закодированного url истек');
    }
}
