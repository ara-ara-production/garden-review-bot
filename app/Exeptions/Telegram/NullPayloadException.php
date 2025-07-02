<?php

namespace App\Exeptions\Telegram;

class NullPayloadException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Cant find payload");
    }
}
