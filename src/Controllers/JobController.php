<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Repositories\JobRepository;
use Milos\JobsApi\Services\Filter;

class JobController
{
    #[Route(method: 'get', path: '/api/v1/jobs', name: 'getAllJobs')]
    public function getAllJobs(Request $req): JSONResponse
    {
        $jobRepo = new JobRepository();
        $jobs = $jobRepo->getAll();

        return new JSONResponse([
            'status' => 'success',
            'results' => count($jobs),
            'data' => [
                'jobs' => $jobs
            ]
        ]);
    }

    #[Route(method: 'get', path: '/api/v1/jobs/{id}', name: 'getJobById')]
    public function getJobById(Request $req): JSONResponse
    {
        $jobRepo = new JobRepository();
        $id = $req->getUrlParams()['id'];
        $job = $jobRepo->getById($id);

        return new JSONResponse([
            'status' => 'success',
            'data' => [
                'job' => $job
            ]
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/jobs/filter', name: 'filterJobs')]
    public function filterJobs(Request $req): JSONResponse
    {
        $jobRepo = new JobRepository();
        $filter = Filter::create($req->body);
        $filteredJobs = $jobRepo->filterJobs($filter);

        return new JSONResponse([
            'status' => 'success',
            'results' => count($filteredJobs),
            'data' => [
                'jobs' => $filteredJobs
            ]
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/jobs', name: 'createJob')]
    public function createJob(Request $req): JSONResponse
    {
        $jobRepo = new JobRepository();
        $newJob = $jobRepo->create($req->body);

        $res = new JSONResponse([
            'status' => 'success',
            'message' => 'job created!',
            'data' => [
                'job' => $newJob
            ]
        ]);
        $res->statusCode(201);
        return $res;
    }

    #[Route(method: 'delete', path: '/api/v1/jobs/{id}', name: 'deleteJob')]
    public function deleteJob(Request $req): JSONResponse
    {
        $jobRepo = new JobRepository();
        $id = $req->getUrlParams()['id'];
        $status = $jobRepo->delete($id);

        if (!$status) {
            throw new APIException('no job deleted!', 400);
        }

        $res = new JSONResponse([
            'status' => 'success',
            'message' => 'job deleted!',
        ]);
        $res->statusCode(204);
        return $res;
    }
}