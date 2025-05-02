<?php

namespace Milos\JobsApi\DTOs;

class EmployerDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $basedIn,
        public readonly string $description,
    ) {}
}