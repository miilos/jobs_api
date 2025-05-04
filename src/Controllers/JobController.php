<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Repositories\JobRepository;

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
        $filteredJobs = $jobRepo->filterJobs($req->filter);

        return new JSONResponse([
            'status' => 'success',
            'results' => count($filteredJobs),
            'data' => [
                'jobs' => $filteredJobs
            ]
        ]);
    }
}