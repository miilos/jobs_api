<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;
use Milos\JobsApi\Core\QueryDirector;

class CommentModel extends Model
{
    public function __construct(QueryDirector $director)
    {
        parent::__construct($director);
    }

    public function getCommentsForJob($jobId): array
    {
        return $this->director->getAll('comments', ['*'], ['job_id' => $jobId]);
    }
}