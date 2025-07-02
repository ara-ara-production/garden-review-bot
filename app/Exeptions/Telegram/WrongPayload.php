<?php

namespace App\Exeptions\Telegram;

class WrongPayload extends \Exception
{
    public function __construct(string $must, string $reality)
    {
        parent::__construct("callback payload is incorrect must be {$must}, but {$reality} get");
    }
}
