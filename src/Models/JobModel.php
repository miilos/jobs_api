<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;
use Ramsey\Uuid\Uuid;

class JobModel
{
    public function getAllJobs(): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('jobs');
        return $qb->execute();
    }

    public function getJobById(string $id): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('jobs');
        $qb->where(['jobId' => $id]);
        $job = $qb->execute('one');

        if (!$job) {
            return [];
        }

        return $job;
    }

    public function getJobsByEmployer(string $employerId): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('jobs');
        $qb->where(['employerId' => $employerId]);
        return $qb->execute();
    }

    public function createJob(array $job): array
    {
        $jobId = Uuid::uuid4();
        $qb = new QueryBuilder();
        $qb->insert();
        $qb->table('jobs');
        $qb->fields('jobId', 'employerId', 'jobName', 'description', 'field', 'startSalary', 'shifts', 'location', 'flexibleHours', 'workFromHome');
        $qb->values([
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
        $status = $qb->execute();

        if ($status) {
            return $this->getJobById($jobId);
        }
        else {
            return [];
        }
    }

    public function updateJob(string $id, array $data): array
    {
        $qb = new QueryBuilder();
        $qb->update();
        $qb->table('jobs');
        $qb->values($data);
        $qb->where(['jobId' => $id]);
        $status = $qb->execute();

        if ($status) {
            return $this->getJobById($id);
        }
        else {
            return [];
        }
    }

    public function deleteJob(string $id): bool
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table('jobs');
        $qb->where(['jobId' => $id]);
        return $qb->execute();
    }
}