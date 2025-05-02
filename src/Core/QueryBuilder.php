<?php

namespace Milos\JobsApi\Core;

class QueryBuilder
{
    private string $query;
    private string $operation;
    private string $table;
    private string $join = '';
    private string $fields = '';
    private array $bindings = [];
    private string $conditionsString = '';
    private string $valuesString = '';
    private $conn;
    private $stmt;

    public function operation(string $operation): void
    {
        $this->operation = $operation;
    }

    public function select(mixed ...$fields): void
    {
        $this->operation = 'SELECT';
        // $fields need to be destructured because $this->fields() is a variadic function
        // so it will wrap the array inside another array
        $this->fields(...$fields);
    }

    public function insert(): void
    {
        $this->operation = 'INSERT';
    }

    public function update(): void
    {
        $this->operation = 'UPDATE';
    }

    public function delete(): void
    {
        $this->operation = 'DELETE';
    }

    public function table(string $table): void
    {
        $this->table = $table;
    }

    public function join(string $joinType, string $joinTable, string $baseTableField, string $joinTableField, string $operation = '='): void
    {
        $baseTableQualifier = $this->table[0];
        $joinTableQualifier = $joinTable[0];
        $previousJoinTableQualifier = '';

        if ($this->join) {
            // if a join already exists, $baseTableField refers to the field from the last table joined
            $this->join .= " $joinType $joinTable $joinTableQualifier
                           ON $baseTableField $operation $joinTableField";
        }
        else {
            $this->join .= " $baseTableQualifier $joinType $joinTable $joinTableQualifier 
                           ON $baseTableField $operation $joinTableField";
        }
    }

    public function fields(mixed ...$fields): void
    {
        foreach ($fields as $field) {
            if (is_string($field)) {
                $this->fields .= "$field, ";
            }

            if (is_array($field)) {
                $this->fields .= (substr($field['table'], 0, 1) . '.' . $field['field'] . ', ');
            }
        }

        $this->fields = substr($this->fields, 0, -2);
    }

    public function values(array $data): void
    {
        foreach ($data as $field => $value) {
            $this->bindings[] = [ $field => $value ];

            if ($this->operation === 'INSERT') {
                $this->valuesString .= ":$field, ";
            }

            if ($this->operation === 'UPDATE') {
                $this->valuesString .= "$field = :$field, ";
            }
        }
        $this->valuesString = substr($this->valuesString, 0, -2);
    }

    public function where(array $condition, string $operation = '=', string $separator = 'AND', string $table = ''): void
    {
        $this->bindings[] = $condition;

        if ($table) {
            $this->conditionsString .= ($table[0] . '.' . key($condition) . " $operation :" . key($condition) . " $separator ");
        }
        else {
            $this->conditionsString .= (key($condition) . " $operation :" . key($condition) . " $separator ");
        }
    }

    private function build(): void
    {
        switch ($this->operation) {
            case 'SELECT':
                $this->buildSelect();
                break;
            case 'INSERT':
                $this->buildInsert();
                break;
            case 'UPDATE':
                $this->buildUpdate();
                break;
            case 'DELETE':
                $this->buildDelete();
                break;
        }
    }

    private function buildSelect(): void
    {
        $this->query = "$this->operation $this->fields FROM $this->table $this->join";

        if ($this->conditionsString) {
            // remove trailing condition from $conditionsString
            $this->conditionsString = substr($this->conditionsString, 0, -4);
            $this->query .= " WHERE $this->conditionsString";
        }
    }

    private function buildInsert(): void
    {
        $this->query = "$this->operation INTO $this->table ($this->fields) VALUES ($this->valuesString)";
    }

    private function buildUpdate(): void
    {
        $this->query = "$this->operation $this->table SET $this->valuesString";

        if ($this->conditionsString) {
            $this->conditionsString = substr($this->conditionsString, 0, -4);
            $this->query .= " WHERE $this->conditionsString";
        }
    }

    private function buildDelete(): void
    {
        $this->query = "$this->operation FROM $this->table";

        if ($this->conditionsString) {
            $this->conditionsString = substr($this->conditionsString, 0, -4);
            $this->query .= " WHERE $this->conditionsString";
        }
    }

    public function execute(string $fetch = 'all', int $fetchMode = \PDO::FETCH_ASSOC): array|bool
    {
        $this->build();

        $this->conn = (new Db())->getConnection();

        $this->stmt = $this->conn->prepare($this->query);

        if ($this->bindings) {
            foreach ($this->bindings as $binding) {
                $this->stmt->bindValue(':' . key($binding), $binding[key($binding)]);
            }
        }

        $this->stmt->execute();

        $results = null;
        if ($this->operation === 'SELECT') {
            if ($fetchMode === \PDO::FETCH_COLUMN) {
                $results = $this->stmt->fetchAll($fetchMode, 0);
            }
            else if ($fetch === 'all') {
                $results = $this->stmt->fetchAll($fetchMode);
            }
            else {
                $results = $this->stmt->fetch($fetchMode);
            }
        }
        else {
            $results = $this->stmt->rowCount() > 0;
        }

        $this->close();
        return $results;
    }

    private function close(): void
    {
        $this->stmt = null;
        $this->conn = null;
    }
}