<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Repositories\UserRepository;
use Milos\JobsApi\Services\JWTHandler;

class UserController
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    #[Route(method: 'post', path: '/api/v1/signup', name: 'signup')]
    public function signup(Request $req): JSONResponse
    {
        $data = $req->body;
        $newUser = $this->repo->signup($data);

        $token = JWTHandler::sign($newUser);
        JWTHandler::setCookie($token);

        return new JSONResponse([
            'status' => 'success',
            'message' => 'signed up!',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/login', name: 'login')]
    public function login(Request $req): JSONResponse
    {
        $body = $req->body;
        $user = $this->repo->login($body);

        $token = JWTHandler::sign($user);
        JWTHandler::setCookie($token);

        return new JSONResponse([
            'status' => 'success',
            'message' => 'logged in!',
            'data' => [
                'token' => $token
            ]
        ]);
    }
}