<?php

namespace Milos\JobsApi\Core;

class QueryDirector
{
    public function getAll(string $table, array $fields, array $conditions = []): array
    {
        $qb = new QueryBuilder();
        $qb->select(...$fields);
        $qb->table($table);

        foreach ($conditions as $field => $value) {
            $qb->where([$field => $value]);
        }

        return $qb->execute();
    }

    public function getOne(string $table, array $fields, array $conditions): array
    {
        $qb = new QueryBuilder();
        $qb->select(...$fields);
        $qb->table($table);

        foreach ($conditions as $field => $value) {
            $qb->where([$field => $value]);
        }

        $record = $qb->execute('one');

        if (!$record) {
            return [];
        }

        return $record;
    }

    public function create(string $table, array $data): bool
    {
        $qb = new QueryBuilder();
        $qb->insert();
        $qb->table($table);
        $qb->fields(...array_keys($data));
        $qb->values($data);
        return $qb->execute();
    }

    public function update(string $table, array $data, array $conditions): bool
    {
        $qb = new QueryBuilder();
        $qb->update();
        $qb->table($table);
        $qb->values($data);

        foreach ($conditions as $field => $value) {
            $qb->where([$field => $value]);
        }

        return $qb->execute();
    }

    public function delete(string $table, array $conditions): bool
    {
        $qb = new QueryBuilder();
        $qb->delete();
        $qb->table($table);

        foreach ($conditions as $field => $value) {
            $qb->where([$field => $value]);
        }

        return $qb->execute();
    }
}