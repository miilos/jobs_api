<?php

namespace Milos\JobsApi\Core;

use Milos\JobsApi\DTOs\UserDTO;

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

    public function getSortProperties(): array
    {
        $sortProps = [];

        if (isset($_GET['sort'])) {
            $props = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_SPECIAL_CHARS);
            $sortProps['properties'] = explode(',', $props);
        }

        if (isset($_GET['sortDirection'])) {
            $sortProps['direction'] = filter_input(INPUT_GET, 'sortDirection', FILTER_SANITIZE_SPECIAL_CHARS);

            // in the case of invalid input, ignore it and just set the direction to 'asc'
            if ($sortProps['direction'] !== 'asc' && $sortProps['direction'] !== 'desc') {
                $sortProps['direction'] = 'asc';
            }
        }
        else {
            $sortProps['direction'] = 'asc';
        }

        return $sortProps;
    }
}