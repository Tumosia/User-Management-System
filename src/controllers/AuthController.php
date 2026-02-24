<?php

require_once(__DIR__ . '/../config/Database.php');

class AuthController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $this->conn->query($sql);

            if($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                if(password_verify($password, $user['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    return ['success' => true, 'message' => 'Login successful!'];
                } else {
                    return ['success' => false, 'message' => 'Invalid password'];
                }
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Login error'];
        }
    }

    public function register($name, $email, $password) {
        try {
            $name = addslashes($name);
            $email = addslashes($email);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if email exists
            $check_sql = "SELECT * FROM users WHERE email = '$email'";
            $check_result = $this->conn->query($check_sql);

            if($check_result->num_rows > 0) {
                return ['success' => false, 'message' => 'Email already registered'];
            }

            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            $result = $this->conn->query($sql);

            if($result === TRUE) {
                return ['success' => true, 'message' => 'Registration successful! Please login.'];
            } else {
                return ['success' => false, 'message' => 'Registration failed'];
            }
        } catch (Exception $ex) {
            file_put_contents(__DIR__ . '/../../logs/error.txt', $ex);
            return ['success' => false, 'message' => 'Registration error'];
        }
    }

    public function logout() {
        session_destroy();
        return true;
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
}
?>
