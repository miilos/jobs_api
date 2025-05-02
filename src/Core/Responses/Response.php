<?php

namespace Milos\JobsApi\Core\Responses;

abstract class Response
{
    protected int $statusCode;

    public function statusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public abstract function send();
}