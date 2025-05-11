<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\DTOs\UserDTO;
use Milos\JobsApi\Mappers\UserMapper;
use Milos\JobsApi\Models\UserModel;
use Milos\JobsApi\Services\Validator;

class UserRepository
{
    private UserModel $model;
    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    public function signup($data): UserDTO
    {
        $validator = new Validator($data);

        $errors = $validator->validate('users');

        if ($errors) {
            throw new APIException('validation error', 400, $errors);
        }

        $user = $this->model->signup($data);

        if (!$user) {
            throw new APIException('something went wrong with signing you up', 400);
        }

        return UserMapper::toDTO($user);
    }

    public function login($data): UserDTO
    {
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new APIException('you need to specify email and password to log in', 400);
        }

        $user = $this->model->getUserByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new APIException('incorrect email or password', 403);
        }

        return UserMapper::toDTO($user);
    }
}