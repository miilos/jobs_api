<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;

class EmployerModel
{
    public function getAllEmployers(): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('employers');
        return $qb->execute();
    }

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