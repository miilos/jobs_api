<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Response;
use Milos\JobsApi\Core\Route;

class JobController
{
    #[Route(method: 'get', path: '/api/v1/jobs', name: 'getAllJobs')]
    public function getAllJobs(Request $req, Response $res): string
    {
        return $res->statusCode(200)->sendJSON([
           'status' => 'success',
           'message' => 'hello world!'
        ]);
    }
}