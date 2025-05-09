<?php

namespace Milos\JobsApi\Core;

use Milos\JobsApi\DTOs\UserDTO;
use Milos\JobsApi\Services\Filter;

class Request
{
    private array $urlParams = [];
    public array $body = [];
    public ?UserDTO $user = null; // when authorize middleware runs, it will add the logged in user's data to the request

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function setUrlParams(array $urlParams): void
    {
        $this->urlParams = $urlParams;
    }

    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    public function getBody(): array
    {
        $body = [];

        foreach ($_POST as $key => $value) {
            $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $body;
    }

    public function getPath(): string
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }
}