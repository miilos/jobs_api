<?php

namespace Milos\JobsApi\Mappers;

use Milos\JobsApi\DTOs\UserDTO;

class UserMapper
{
    public static function toDTO(array $data): UserDTO
    {
        return new UserDTO(
            id: $data['userId'],
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            email: $data['email'],
            field: $data['field'],
            role: $data['role'],
        );
    }
+
    public static function fromStdClass(\stdClass $userObj): UserDTO
    {
        return new UserDTO(
            id: $userObj->id,
            firstName: $userObj->firstName,
            lastName: $userObj->lastName,
            email: $userObj->email,
            field: $userObj->field,
            role: $userObj->role,
        );
    }
}