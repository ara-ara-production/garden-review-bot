<?php

namespace App\Exeptions\Telegram;

class NullUsernameException extends \Exception
{
    public function __construct()
    {
        parent::__construct("telegram username cannot be null");
    }
}
