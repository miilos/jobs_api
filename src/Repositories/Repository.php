<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\DTOs\DTOInterface;

interface Repository
{
    public function getAll(): array;
    public function getById(string $id): object;
    public function create(array $data): DTOInterface;
    public function update(string $id, array $data): DTOInterface;
    public function delete(string $id): bool;
}