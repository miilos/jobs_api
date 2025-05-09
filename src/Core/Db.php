<?php

namespace Milos\JobsApi\Core;

use PDO;

class Db
{
    private static ?Db $instance = null;
    private string $host;
    private string $user;
    private string $pass;
    private string $database;

    private $dbh;

    private function __construct()
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

    public static function getInstance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}