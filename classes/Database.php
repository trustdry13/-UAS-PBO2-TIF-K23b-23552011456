<?php
class Database {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function getLastInsertId() {
        return $this->connection->insert_id;
    }

    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    public function __destruct() {
        $this->connection->close();
    }
}