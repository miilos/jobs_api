<?php

namespace Milos\JobsApi\Middleware;

use Milos\JobsApi\Core\Request;

class AuthMiddleware
{
    #[Middleware(method: 'get', path: '/api/v1/jobs', priority: 1)]
    public function authorize(Request $req)
    {

    }

    #[Middleware(method: 'get', path: '/api/v1/jobs', priority: 2)]
    public function protect(Request $req)
    {

    }
}