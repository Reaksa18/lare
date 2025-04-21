<?php
class Databases {
    private $host = "localhost";
    private $dbname = "phpproject";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password) {
        $username = trim($username);
        $passwordHash = password_hash(trim($password), PASSWORD_BCRYPT);

        // Check if username exists
        $sql = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Username has already been taken!";
        }

        // Insert new user
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            return "Error: " . implode(", ", $stmt->errorInfo());
        }

        return "Registration successful!";
    }

    public function login($username, $password) {
        $sql = "SELECT id, password FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                session_start();

                // Generate a secure random token
                $token = bin2hex(random_bytes(32));

                // Set token expiration (7 days from now)
                $expiry = date('Y-m-d H:i:s', strtotime('+7 days'));

                // Store the token in the session
                $_SESSION["user_token"] = $token;
                $_SESSION["user_id"] = $row['id'];

                // Save the token and expiry in the database
                $updateSql = "UPDATE users SET token = :token, token_expiry = :expiry WHERE id = :id";
                $updateStmt = $this->conn->prepare($updateSql);
                $updateStmt->bindParam(':token', $token, PDO::PARAM_STR);
                $updateStmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
                $updateStmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
                $updateStmt->execute();

                echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
                exit;
            } else {
                echo "<script>alert('Invalid password!');</script>";
            }
        } else {
            echo "<script>alert('Username not found!');</script>";
        }
    }

    public function isAuthenticated() {
        // session_start();  //close cuz errro in calling header in product detail///
        if (!isset($_SESSION["user_token"])) {
            return false;
        }

        // Validate the token and check expiry
        $sql = "SELECT id FROM users WHERE token = :token AND token_expiry > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':token', $_SESSION["user_token"], PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }




    public function logout() {
        session_start();
        if (isset($_SESSION["user_id"])) {
            // Remove token from the database
            $sql = "UPDATE users SET token = NULL, token_expiry = NULL WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->execute();
        }

        // Destroy session
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }

    public function getUsernameByToken($token) {
        $sql = "SELECT username FROM users WHERE token = :token AND token_expiry > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['username']; // Return username if valid token found
        }
        return null; // Return null if no valid token found
    }
}


?>