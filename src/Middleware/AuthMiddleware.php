<?php

namespace Milos\JobsApi\Middleware;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Mappers\UserMapper;
use Milos\JobsApi\Services\JWTHandler;

class AuthMiddleware
{
    public function authorize(Request $req, array $args): void
    {
        $jwt = JWTHandler::getTokenFromCookie();

        if (!$jwt) {
            throw new APIException('you need to be logged in to access this route', 401);
        }

        $user = JWTHandler::decode($jwt);
        $req->user = UserMapper::fromStdClass($user);
    }

    public function protect(Request $req, array $args): void
    {
        if (!$req->user || !in_array($req->user->role, $args['allowedRoles'])) {
            throw new APIException('you don\'t have permission to access this route!', 403);
        }
    }
}