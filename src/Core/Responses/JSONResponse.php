<?php

namespace Milos\JobsApi\Core\Responses;

class JSONResponse extends Response
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function addResponseData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function send(): string
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        return json_encode($this->data);
    }
}