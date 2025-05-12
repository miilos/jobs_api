<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\DTOs\EmployerDTO;
use Milos\JobsApi\DTOs\JobDTO;
use Milos\JobsApi\Mappers\EmployerMapper;
use Milos\JobsApi\Mappers\JobMapper;
use Milos\JobsApi\Models\EmployerModel;
use Milos\JobsApi\Models\JobModel;
use Milos\JobsApi\Services\Validator;

class EmployerRepository implements Repository
{
    private EmployerModel $model;

    public function __construct(EmployerModel $model){
        $this->model = $model;
    }

    public function getAll(): array
    {
        $employers = $this->model->getAllEmployers();

        $employerDTOs = [];
        foreach ($employers as $employer) {
            $employerDTOs[] = EmployerMapper::toDTO($employer);
        }

        return $employerDTOs;
    }

    public function getById(string $id): object
    {
        $employer = $this->model->getEmployerById($id);

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

    public function create(array $data): EmployerDTO
    {
        $validator = new Validator($data);
        $errors = $validator->validate('employers');

        if ($errors) {
            throw new APIException('validation error', 400, $errors);
        }

        $newEmployer = $this->model->createEmployer($data);

        if (!$newEmployer) {
            throw new APIException('something went wrong with creating the employer', 500);
        }

        return EmployerMapper::toDTO($newEmployer);
    }

    public function update(string $id, array $data): EmployerDTO
    {
        $validator = new Validator($data);
        $errors = $validator->validate('employers', options: [
            'check' => array_keys($data)
        ]);

        if ($errors) {
            throw new APIException('validation error', 400, $errors);
        }

        $updatedEmployer = $this->model->updateEmployer($id, $data);

        if (!$updatedEmployer) {
            throw new APIException('something went wrong with updating the employer', 500);
        }

        return EmployerMapper::toDTO($updatedEmployer);
    }

    public function delete(string $id): void
    {
        $status = $this->model->deleteEmployer($id);

        if (!$status) {
            throw new APIException('no employer deleted!', 400);
        }
    }
}