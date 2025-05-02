<?php

namespace Milos\JobsApi\Mappers;

use Milos\JobsApi\DTOs\EmployerDTO;

class EmployerMapper
{
    public static function toDTO(array $employer): EmployerDTO
    {
        return new EmployerDTO(
            id: $employer['employerId'],
            name: $employer['employerName'],
            basedIn: $employer['basedIn'],
            description: $employer['employerDescription'],
        );
    }
}