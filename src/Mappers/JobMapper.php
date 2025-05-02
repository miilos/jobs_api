<?php

namespace Milos\JobsApi\Mappers;

use Milos\JobsApi\DTOs\JobDTO;

class JobMapper
{
    public static function toDTO($job): JobDTO
    {
        return new JobDTO(
            id: $job['jobId'],
            name: $job['jobName'],
            description: $job['description'],
            field: $job['field'],
            startSalary: $job['startSalary'],
            shifts: $job['shifts'],
            location: $job['location'],
            createdAt: new \DateTime($job['createdAt']),
            flexibleHours: $job['flexibleHours'],
            workFromHome: $job['workFromHome'],
        );
    }
}