<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryDirector;
use Ramsey\Uuid\Nonstandard\Uuid;

class EmployerModel extends Model
{
    public function __construct(QueryDirector $director)
    {
        parent::__construct($director);
    }

    public function getAllEmployers(): array
    {
        return $this->director->getAll('employers', ['*']);
    }

    public function getEmployerById(string $id): array
    {
        return $this->director->getOne('employers', ['*'], ['employerId' => $id]);
    }

    public function createEmployer(array $data): array
    {
        $id = Uuid::uuid4();
        $status = $this->director->create('employers', [
            'employerId' => $id,
            'employerName' => $data['employerName'],
            'basedIn' => $data['basedIn'],
            'employerDescription' => $data['employerDescription']
        ]);

        if ($status) {
            return $this->getEmployerById($id);
        }
        else {
            return [];
        }
    }

    public function updateEmployer(string $id, array $data): array
    {
        $status = $this->director->update('employers', $data, ['employerId' => $id]);

        if ($status) {
            return $this->getEmployerById($id);
        }
        else {
            return [];
        }
    }

    public function deleteEmployer(string $id): bool
    {
        return $this->director->delete('employers', ['employerId' => $id]);
    }
}