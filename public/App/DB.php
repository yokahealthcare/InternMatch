<?php

namespace App;

use PDO;

class DB
{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'internmatch';

    public function connect(): PDO
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

    public function fetchAll($sql): bool|array
    {
        $conn = $this->connect();
        $stmt = $conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}