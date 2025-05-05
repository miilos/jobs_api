<?php

namespace Milos\JobsApi\Repositories;

use Milos\JobsApi\DTOs\JobDTO;

interface IRepositoryMethods
{
    public function getAll(): array;
    public function getById(string $id): object;
    public function create(array $data): JobDTO;
    public function update(string $id, array $data): JobDTO;
    public function delete(string $id): bool;
}