<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Middleware\AuthMiddleware;
use Milos\JobsApi\Middleware\Middleware;
use Milos\JobsApi\Repositories\EmployerRepository;

class EmployerController
{
    private EmployerRepository $repo;

    public function __construct(EmployerRepository $repo)
    {
        $this->repo = $repo;
    }

    #[Route(method: 'get', path: '/api/v1/employers', name: 'getAllEmployers')]
    public function getAllEmployers(Request $req): JSONResponse
    {
        $employers = $this->repo->getAll();

        return new JSONResponse([
            'status' => 'success',
            'results' => count($employers),
            'data' => [
                'employers' => $employers
            ]
        ]);
    }

    #[Route(method: 'get', path: '/api/v1/employers/{id}', name: 'getEmployerById')]
    public function getEmployerById(Request $req): JSONResponse
    {
        $id = $req->getUrlParams()['id'];
        $employer = $this->repo->getById($id);

        return new JSONResponse([
            'status' => 'success',
            'data' => [
                'employer' => $employer
            ]
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/employers', name: 'createEmployer')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    #[Middleware(function: [AuthMiddleware::class, 'protect'], args: ['allowedRoles' => ['admin']])]
    public function createEmployer(Request $req): JSONResponse
    {
        $employer = $this->repo->create($req->body);

        $res = new JSONResponse([
            'status' => 'success',
            'message' => 'employer created!',
            'data' => [
                'employer' => $employer
            ]
        ]);
        $res->statusCode(201);
        return $res;
    }
}