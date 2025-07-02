<?php

namespace App\Exeptions\Api;

class NullReviewsException extends \Exception
{
    public function __construct()
    {
        parent::__construct("reviews dto not set");
    }
}
