<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Repositories\EmployerRepository;

class EmployerController
{
    #[Route(method: 'get', path: '/api/v1/employers', name: 'getAllEmployers')]
    public function getAllEmployers(Request $req): JSONResponse
    {
        $employerRepo = new EmployerRepository();
        $employers = $employerRepo->getAll();

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
        $employerRepo = new EmployerRepository();
        $employer = $employerRepo->getById($id);

        return new JSONResponse([
            'status' => 'success',
            'data' => [
                'employer' => $employer
            ]
        ]);
    }
}