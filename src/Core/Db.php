<?php

namespace Milos\JobsApi\Core;

use PDO;
use PDOException;

class Db
{
    private string $host = "db";
    private string $user = "milos";
    private string $pass = "milospassword123@!";
    private string $database = "praksa-projekat-1";

    private $dbh;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            var_dump($e->getMessage());
            //ErrorController::redirectToErrorPage('db-error');
        }
        catch (\Throwable $t) {
            ErrorController::redirectToErrorPage('unknown-error');
        }
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}