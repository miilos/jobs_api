<?php

namespace Milos\JobsApi\Core\Exceptions;

class APIException extends \Exception
{
    private int $statusCode;
    public function __construct(string $message, int $statusCode)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}