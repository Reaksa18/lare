<?php
// slider.php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbName = "phpproject";
    public $conn;

    // Constructor to establish a PDO connection
    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbName}", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Close the database connection
    public function close() {
        $this->conn = null;
    }

    // Select records from a table
    public function select($table, $columns = "*", $criteria = "") {
        $query = "SELECT $columns FROM $table";
        if ($criteria) {
            $query .= " WHERE $criteria";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a record into the database
    public function insert($table, $data = []) {
        $fields = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        // Bind values
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Update records in a table
    public function update($table, $data = [], $criteria = "") {
        $setClause = "";
        foreach ($data as $field => $value) {
            $setClause .= "$field = :$field, ";
        }
        $setClause = rtrim($setClause, ", ");

        $query = "UPDATE $table SET $setClause WHERE $criteria";
        $stmt = $this->conn->prepare($query);

        // Bind values
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Delete records from the database
    public function delete($table, $criteria) {
        $query = "DELETE FROM $table WHERE $criteria";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Count records in a table
    public function count($table = "", $criteria = "") {
        $query = "SELECT COUNT(*) FROM $table";
        if ($criteria) {
            $query .= " WHERE $criteria";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
// Create an instance of the Database class
$db = new Database();
// Call the select method
$result = $db->select('tbl_slideshow', '*', "enable='1'", 'ORDER BY ssorder ASC');
$num = count($result);


// shop.php

// Database connection using PDO (OOP)
class Database2 {
    private $host = "localhost";
    private $db_name = "phpproject";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Error handling
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Select records from a table
    public function select($table, $columns = "*", $criteria = "") {
        $query = "SELECT $columns FROM $table";
        if ($criteria) {
            $query .= " WHERE $criteria";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a record into the database
    public function insert($table, $data = []) {
        $fields = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
}

// Create database connection
$database = new Database();
$conn = $database->conn;

// Fetch products
$products = $database->select("products");


// cart.php


// Database Connection Class
class Database3 {
    private $host = "localhost";
    private $dbname = "phpproject";
    private $username = "root";
    private $password = "";
    private static $instance = null;
    public $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

// Cart Class for handling cart actions
class Cart {
    private $pdo;

    public function __construct() {
        $this->pdo = Database3::getInstance()->pdo;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function getCartCount() {
        return isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
    }

    public function addToCart($product_id) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :product_id");
            $stmt->execute(['product_id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($product) {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $product['price'],
                    'qty' => 1  // Always set quantity to 1
                ];
            }
        } else {
            $_SESSION['cart'][$product_id]['qty'] = 1;  // Reset to 1 if product already in cart
        }
    }

    public function getCartItems() {
        return $_SESSION['cart'];
    }

    public function removeFromCart($product_id) {
        unset($_SESSION['cart'][$product_id]);
    }

    public function updateQuantity($product_id, $qty) {
        if (isset($_SESSION['cart'][$product_id]) && $qty >= 1) {
            $_SESSION['cart'][$product_id]['qty'] = $qty;
        } elseif ($qty == 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Cart Operations
$cart = new Cart();

// Handling different cart actions based on URL parameters
if (isset($_GET['get_cart_count'])) {
    echo $cart->getCartCount();
    exit;
}

if (isset($_GET['add_to_cart']) && isset($_GET['product_id'])) {
    $cart->addToCart($_GET['product_id']);
    exit;
}

if (isset($_GET['remove']) && isset($_GET['product_id'])) {
    $cart->removeFromCart($_GET['product_id']);
    header("Location: cart.php");
    exit;
}

if (isset($_GET['update_quantity']) && isset($_GET['product_id']) && isset($_GET['qty'])) {
    $cart->updateQuantity($_GET['product_id'], $_GET['qty']);
    $total = 0;
    foreach ($_SESSION['cart'] as $product) {
        $total += $product['price'] * $product['qty'];
    }
    echo json_encode(['success' => true, 'total' => $total, 'cartCount' => $cart->getCartCount()]);
    exit;
}

// Get all cart items
$cartItems = $cart->getCartItems();



?>
