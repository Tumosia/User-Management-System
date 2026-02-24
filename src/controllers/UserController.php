<?php

require_once(__DIR__ . '/../config/Database.php');

class UserController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllUsers() {
        try {
            $sql = "SELECT * FROM users";
            return $this->conn->query($sql);
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return null;
        }
    }

    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = '$id'";
            $result = $this->conn->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return null;
        }
    }

    public function addUser($name, $email, $password = '12345678') {
        try {
            $name = addslashes($name);
            $email = addslashes($email);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            $result = $this->conn->query($sql);

            if($result === TRUE) {
                return ['success' => true, 'message' => 'User added successfully'];
            } else {
                return ['success' => false, 'message' => 'Error adding user!'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Error adding user!'];
        }
    }

    public function updateUser($id, $name, $email) {
        try {
            $name = addslashes($name);
            $email = addslashes($email);

            $sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$id'";
            $result = $this->conn->query($sql);

            if($result === TRUE) {
                return ['success' => true, 'message' => 'User updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Error updating user!'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Error updating user!'];
        }
    }

    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM users WHERE id = '$id'";
            $result = $this->conn->query($sql);

            if($result === TRUE) {
                return ['success' => true, 'message' => 'User deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Error deleting user!'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Server Error!'];
        }
    }

    public function deleteMultiple($ids) {
        try {
            $sql = "DELETE FROM users WHERE id IN ($ids)";
            $result = $this->conn->query($sql);

            if($result === TRUE) {
                return ['success' => true, 'message' => 'Users deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Error deleting users!'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Server Error!'];
        }
    }
}
?>
