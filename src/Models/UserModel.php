<?php

namespace Milos\JobsApi\Models;

use Milos\JobsApi\Core\QueryBuilder;
use Ramsey\Uuid\Uuid;

class UserModel
{
    public function signup(array $data): array
    {
        $id = Uuid::uuid4();
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $qb = new QueryBuilder();
        $qb->insert();
        $qb->table('users');
        $qb->fields('userId', 'firstName', 'lastName', 'email', 'password', 'field');
        $qb->values([
            'userId' => $id,
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => $password,
            'field' => $data['field']
        ]);
        $status = $qb->execute();

        if ($status) {
            return $this->getUserById($id);
        }
        else {
            return [];
        }
    }

    public function getUserById(string $id): array
    {
        $qb = new QueryBuilder();
        $qb->select('userId', 'firstName', 'lastName', 'email', 'field', 'role');
        $qb->table('users');
        $qb->where(['userId' => $id]);
        $user = $qb->execute('one');

        if (!$user) {
            return [];
        }
        else {
            return $user;
        }
    }

    public function getUserByEmail(string $email): array
    {
        $qb = new QueryBuilder();
        $qb->select('*');
        $qb->table('users');
        $qb->where(['email' => $email]);
        $user = $qb->execute('one');

        if (!$user) {
            return [];
        }
        else {
            return $user;
        }
    }
}