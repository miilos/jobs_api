<?php

namespace Milos\JobsApi\Core;

class APIException extends \Exception
{
    public int $statusCode;
    public function __construct(string $message, int $statusCode)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}