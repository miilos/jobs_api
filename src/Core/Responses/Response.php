<?php

namespace Milos\JobsApi\Core\Responses;

abstract class Response
{
    protected int $statusCode;

    public function statusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode ?? null;
    }

    public abstract function send();
}