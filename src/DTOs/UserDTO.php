<?php

namespace Milos\JobsApi\DTOs;

class UserDTO implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $field,
        private string $role,
    ) {}

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}