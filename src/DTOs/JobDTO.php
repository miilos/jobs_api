<?php

namespace Milos\JobsApi\DTOs;

use DateTime;
use JsonSerializable;

class JobDTO implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $name,
        private string $description,
        private string $field,
        private int $startSalary,
        private int $shifts,
        private string $location,
        private DateTime $createdAt,
        private bool $flexibleHours,
        private bool $workFromHome,
        private ?EmployerDTO $employer = null,
        private array $comments = [],
    ) {}

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function setEmployer(EmployerDTO $employer): void
    {
        $this->employer = $employer;
    }

    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), function($value, $key) {
            // if comments or employers are empty that means they're not needed for the current request,
            // so they're omitted from the JSON response
            if ($key === 'employer' || $key === 'comments') {
                return !empty($value);
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}