<?php

namespace Milos\JobsApi\Repositories;

interface IRepositoryMethods
{
    public function getAll(): array;
    public function getById(string $id): object;
    public function create(array $data): ?array;
    public function update(string $id, array $data): ?array;
    public function delete(string $id): ?bool;
}