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

    public function setPasswordResetToken(string $id, string $token): bool
    {
        return $this->director->update(
            'users',
            [
                'passwordResetToken' => $token,
                'resetExpiresAt' => date('Y-m-d H:i:s', time() + (int) $_ENV['RESET_TOKEN_EXPIRES_IN'])
            ],
            ['userId' => $id]
        );
    }

    public function getUserByPasswordResetToken(string $token): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('users');
        $qb->where(['passwordResetToken' => $token]);
        $qb->where(['resetExpiresAt' => date('Y-m-d H:i:s', time())], operation: '>');
        $user = $qb->execute('one');
        return $user ? $user : [];
    }

    public function updatePassword(string $id, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        return $this->director->update('users', [
            'password' => $hash,
            'passwordResetToken' => null,
            'resetExpiresAt' => null
        ],
        ['userId' => $id]);
    }
}