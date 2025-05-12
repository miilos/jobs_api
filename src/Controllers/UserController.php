<?php

namespace Milos\JobsApi\Controllers;

use Milos\JobsApi\Core\Request;
use Milos\JobsApi\Core\Responses\JSONResponse;
use Milos\JobsApi\Core\Route;
use Milos\JobsApi\Middleware\AuthMiddleware;
use Milos\JobsApi\Middleware\Middleware;
use Milos\JobsApi\Repositories\UserRepository;
use Milos\JobsApi\Services\JWTHandler;
use Milos\JobsApi\Services\Mailer;

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

    #[Route(method: 'get', path: '/api/v1/logout', name: 'logout')]
    public function logout(Request $req): JSONResponse
    {
        JWTHandler::deleteCookie();

        $res = new JSONResponse([
            'status' => 'success',
            'message' => 'logged out!'
        ]);
        $res->statusCode(204);
        return $res;
    }

    #[Route(method: 'get', path: '/api/v1/forgotPassword', name: 'forgotPassword')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    public function forgotPassword(Request $req): JSONResponse
    {
        $email = $req->user->email;
        $userName = $req->user->firstName;
        $resetToken = $this->repo->generatePasswordResetToken($req->user->id);

        $mailer = new Mailer();
        $mailSubject = 'Your password reset token (valid for 10 minutes)';
        $mailBody = 'Your password reset token is: ' . $resetToken . '. To reset your password, submit your new password to this link: http://localhost:8000/api/v1/resetPassword/' . $resetToken;
        $mailer->send(
            $email,
            $userName,
            $mailSubject,
            $mailBody
        );

        return new JSONResponse([
            'status' => 'success',
            'message' => 'password reset token sent to ' . $email
        ]);
    }

    #[Route(method: 'post', path: '/api/v1/resetPassword/{token}', name: 'resetPassword')]
    #[Middleware(function: [AuthMiddleware::class, 'authorize'])]
    public function resetPassword(Request $req): JSONResponse
    {
        $token = $req->getUrlParams()['token'];
        $body = $req->body;
        $this->repo->resetPassword($token, $body);

        return new JSONResponse([
            'status' => 'success',
            'message' => 'password successfully updated!'
        ]);
    }
}