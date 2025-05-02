<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;

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
}