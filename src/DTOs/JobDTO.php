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
        public ?EmployerDTO $employer = null,
        public array $comments = [],
    ) {}

    public function setEmployer(EmployerDTO $employer): void
    {
        $this->employer = $employer;
    }

    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }


    public function jsonSerialize(): mixed
    {
        return array_filter(get_object_vars($this), function($value, $key) {
            if ($key === 'employer' || $key === 'comments') {
                return !empty($value);
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}