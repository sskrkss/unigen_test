<?php

namespace App\Exception;

class UrlNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Url не найден');
    }
}
