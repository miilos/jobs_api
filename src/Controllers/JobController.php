<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Middleware\AuthMiddleware;
use Milos\JobsApi\Middleware\Middleware;
use Milos\JobsApi\Repositories\JobRepository;
use Milos\JobsApi\Services\Filter;

class JobController
{
    private JobRepository $repo;

    public function __construct(JobRepository $repo)
    {
        $this->repo = $repo;
    }

    #[Route(method: 'get', path: '/api/v1/jobs', name: 'getAllJobs')]
    public function getAllJobs(Request $req): JSONResponse
    {
        $jobs = $this->repo->getAll();

        $sortProps = $req->getSortProperties();
        if (isset($sortProps['sort'])) {
            $jobs = $this->repo->sort($jobs, $sortProps);
        }

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
        $id = $req->getUrlParams()['id'];
        $job = $this->repo->getById($id);

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
        $filter = Filter::create($req->body);
        $filteredJobs = $this->repo->filterJobs($filter);

        return new JSONResponse([
            'status' => 'success',
            'results' => count($filteredJobs),
            'data' => [
                'jobs' => $filteredJobs
            ]
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/jobs', name: 'createJob')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    #[Middleware(function: [AuthMiddleware::class, 'protect'], args: ['allowedRoles' => ['admin']])]
    public function createJob(Request $req): JSONResponse
    {
        $newJob = $this->repo->create($req->body);

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

    #[Route(method: 'patch', path: '/api/v1/jobs/{id}', name: 'updateJob')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    #[Middleware(function: [AuthMiddleware::class, 'protect'], args: ['allowedRoles' => ['admin']])]
    public function updateJob(Request $req): JSONResponse
    {
        $id = $req->getUrlParams()['id'];
        $updatedJob = $this->repo->update($id, $req->body);

        return new JSONResponse([
            'status' => 'success',
            'message' => 'job updated!',
            'data' => [
                'job' => $updatedJob
            ]
        ]);
    }

    #[Route(method: 'delete', path: '/api/v1/jobs/{id}', name: 'deleteJob')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    #[Middleware(function: [AuthMiddleware::class, 'protect'], args: ['allowedRoles' => ['admin']])]
    public function deleteJob(Request $req): JSONResponse
    {
        $id = $req->getUrlParams()['id'];
        $this->repo->delete($id);

        $res = new JSONResponse([
            'status' => 'success',
            'message' => 'job deleted!',
        ]);
        $res->statusCode(204);
        return $res;
    }
}