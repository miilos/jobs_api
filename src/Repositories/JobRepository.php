<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\DTOs\JobDTO;
use Milos\JobsApi\Mappers\CommentMapper;
use Milos\JobsApi\Mappers\EmployerMapper;
use Milos\JobsApi\Mappers\JobMapper;
use Milos\JobsApi\Models\CommentModel;
use Milos\JobsApi\Models\EmployerModel;
use Milos\JobsApi\Models\JobModel;
use Milos\JobsApi\Services\Filter;
use Milos\JobsApi\Services\Validator;

class JobRepository implements IRepositoryMethods
{

    public function getAll(): array
    {
        $jobModel = new JobModel();
        $employerModel = new EmployerModel();

        $jobs = $jobModel->getAllJobs();
        $jobDTOs = [];

        foreach ($jobs as $job) {
            $employer = $employerModel->getEmployerById($job['employerId']);
            $employerDTO = null;
            $jobDTO = JobMapper::toDTO($job);

            if ($employer) {
                $employerDTO = EmployerMapper::toDTO($employer);
                $jobDTO->setEmployer($employerDTO);
            }

            $jobDTOs[] = $jobDTO;
        }

        return $jobDTOs;
    }

    public function getById(string $id): JobDTO
    {
        $jobModel = new JobModel();
        $employerModel = new EmployerModel();
        $commentModel = new CommentModel();

        $job = $jobModel->getJobById($id);
        $employer = $employerModel->getEmployerById($job['employerId']);
        $comments = $commentModel->getCommentsForJob($job['jobId']);

        $jobDto = null;

        if (!$job) {
            throw new APIException('no job with that id found!', 400);
        }

        $jobDTO = JobMapper::toDTO($job);

        if ($employer) {
            $employerDTO = EmployerMapper::toDTO($employer);
            $jobDTO->setEmployer($employerDTO);
        }

        if ($comments) {
            $commentDTOs = [];
            foreach ($comments as $comment) {
                $commentDTO = CommentMapper::toDTO($comment);
                $commentDTOs[] = $commentDTO;
            }
            $jobDTO->setComments($commentDTOs);
        };

        return $jobDTO;
    }

    public function filterJobs(Filter $filter): array
    {
        $jobDTOs = $this->getAll();
        return $filter->filterData($jobDTOs);
    }

    public function create(array $data): JobDTO
    {
        $validator = new Validator($data);

        $errors = $validator->validate('jobs');

        if ($errors) {
            throw new APIException('validation error', 400, $errors);
        }

        $jobModel = new JobModel();
        $newJob = $jobModel->createJob($data);

        if (!$newJob) {
            throw new APIException('something went wrong with creating the job', 500);
        }

        return JobMapper::toDTO($newJob);
    }

    public function update(string $id, array $data): JobDTO
    {
        $validator = new Validator($data);
        $errors = $validator->validate('jobs', options: [
            'check' => array_keys($data)
        ]);

        if ($errors) {
            throw new APIException('validation error', 400, $errors);
        }

        $jobModel = new JobModel();
        $updatedJob = $jobModel->updateJob($id, $data);

        if (!$updatedJob) {
            throw new APIException('something went wrong with updating the job', 500);
        }

        return JobMapper::toDTO($updatedJob);
    }

    public function delete(string $id): bool
    {
        $jobModel = new JobModel();
        return $jobModel->deleteJob($id);
    }
}