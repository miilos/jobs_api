<?php

namespace Milos\JobsApi\Core;

use PDO;

class Db
{
    private string $host;
    private string $user;
    private string $pass;
    private string $database;

    private $dbh;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USERNAME'];
        $this->pass = $_ENV['DB_PASSWORD'];
        $this->database = $_ENV['DB_NAME'];

        $this->connect();
    }

    private function connect(): void
    {
        $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}