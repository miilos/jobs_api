<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\DTOs\EmployerDTO;
use Milos\JobsApi\DTOs\JobDTO;
use Milos\JobsApi\Mappers\EmployerMapper;
use Milos\JobsApi\Mappers\JobMapper;
use Milos\JobsApi\Models\EmployerModel;
use Milos\JobsApi\Models\JobModel;

class EmployerRepository implements IRepositoryMethods
{

    public function getAll(): array
    {
        $employerModel = new EmployerModel();
        $employers = $employerModel->getAllEmployers();

        $employerDTOs = [];
        foreach ($employers as $employer) {
            $employerDTOs[] = EmployerMapper::toDTO($employer);
        }

        return $employerDTOs;
    }

    public function getById(string $id): object
    {
        $employerModel = new EmployerModel();
        $employer = $employerModel->getEmployerById($id);

        $jobModel = new JobModel();
        $jobs = $jobModel->getJobsByEmployer($id);

        if (!$employer) {
            throw new APIException('no employer found with that id!', 400);
        }

        $employerDTO = EmployerMapper::toDTO($employer);

        if ($jobs) {
            $jobDTOs = [];
            foreach ($jobs as $job) {
                $jobDTO = JobMapper::toDTO($job);
                $jobDTOs[] = $jobDTO;
            }

            $employerDTO->setJobListings($jobDTOs);
        }

        return $employerDTO;
    }

    public function create(array $data): JobDTO
    {
        // TODO: Implement create() method.
    }

    public function update(string $id, array $data): JobDTO
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }
}