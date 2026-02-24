<?php

class Database {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "crud";
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->server, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>
