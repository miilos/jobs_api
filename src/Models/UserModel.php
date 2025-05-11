<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;
use Milos\JobsApi\Core\QueryDirector;
use Ramsey\Uuid\Uuid;

class UserModel extends Model
{
    public function __construct(QueryDirector $director)
    {
        parent::__construct($director);
    }

    public function signup(array $data): array
    {
        $id = Uuid::uuid4();
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $status = $this->director->create('users', [
            'userId' => $id,
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => $password,
            'field' => $data['field']
        ]);

        if ($status) {
            return $this->getUserById($id);
        }
        else {
            return [];
        }
    }

    public function getUserById(string $id): array
    {
        return $this->director->getOne('users', ['userId', 'firstName', 'lastName', 'email', 'field', 'role'], ['userId' => $id]);
    }

    public function getUserByEmail(string $email): array
    {
        return $this->director->getOne('users', ['*'], ['email' => $email]);
    }
}