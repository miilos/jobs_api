<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;

class CommentModel
{
    public function getCommentsForJob($jobId): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('comments');
        $qb->where(['job_id' => $jobId]);
        return $qb->execute();
    }
}