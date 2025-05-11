<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;
use Milos\JobsApi\Core\QueryDirector;

abstract class Model
{
    protected QueryDirector $director;

    public function __construct(QueryDirector $director)
    {
        $this->director = $director;
    }
}