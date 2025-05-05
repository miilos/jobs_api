<?php

namespace Milos\JobsApi\Core\Exceptions;

class APIException extends \Exception
{
    private int $statusCode;
    private array $data; // in case the error response needs to show a variable
    public function __construct(string $message, int $statusCode, array $data = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getExceptionData(): array
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}