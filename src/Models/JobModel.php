<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryDirector;
use Ramsey\Uuid\Uuid;

class JobModel extends Model
{
    public function __construct(QueryDirector $director)
    {
        parent::__construct($director);
    }

    public function getAllJobs(): array
    {
        return $this->director->getAll('jobs', ['*']);
    }

    public function getJobById(string $id): array
    {
        return $this->director->getOne('jobs', ['*'], ['jobId' => $id]);
    }

    public function getJobsByEmployer(string $employerId): array
    {
        return $this->director->getAll('jobs', ['*'], ['employerId' => $employerId]);
    }

    public function createJob(array $job): array
    {
        $jobId = Uuid::uuid4();
        $status = $this->director->create('jobs', [
            'jobId' => $jobId,
            'employerId' => $job['employerId'],
            'jobName' => $job['jobName'],
            'description' => $job['description'],
            'field' => $job['field'],
            'startSalary' => $job['startSalary'],
            'shifts' => $job['shifts'],
            'location' => $job['location'],
            'flexibleHours' => isset($job['flexibleHours']) ? 1 : 0,
            'workFromHome' => isset($job['workFromHome']) ? 1 : 0
        ]);

        if ($status) {
            return $this->getJobById($jobId);
        }
        else {
            return [];
        }
    }

    public function updateJob(string $id, array $data): array
    {
        $status = $this->director->update('jobs', $data, ['jobId' => $id]);

        if ($status) {
            return $this->getJobById($id);
        }
        else {
            return [];
        }
    }

    public function deleteJob(string $id): bool
    {
        return $this->director->delete('jobs', ['jobId' => $id]);
    }
}