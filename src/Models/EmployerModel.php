<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;

class EmployerModel
{
    public function getEmployerById(string $id): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('employers');
        $qb->where(['employerId' => $id]);
        $employer = $qb->execute('one');

        if (!$employer) {
            return [];
        }

        return $employer;
    }
}