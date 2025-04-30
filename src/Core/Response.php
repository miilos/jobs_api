<?php

namespace Milos\JobsApi\Core;

class Response
{
    private int $statusCode;

    public function statusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function sendJSON(array $data): string
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}