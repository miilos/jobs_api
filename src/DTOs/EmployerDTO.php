<?php

namespace Milos\JobsApi\DTOs;

class EmployerDTO implements DTOInterface, \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private string $basedIn,
        private string $description,
        private array $jobListings = []
    ) {}

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function setJobListings(array $jobListings): void
    {
        $this->jobListings = $jobListings;
    }

    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), function($value, $key) {
            if ($key === 'jobListings') {
                return !empty($value);
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}