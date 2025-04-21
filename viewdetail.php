<?php
// Database Connection Class
class Database {
    private $host = "localhost";
    private $db_name = "phpproject";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Product Class
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Initialize Database Connection
$database = new Database();
$db = $database->getConnection();

// Initialize Product Class
$productObj = new Product($db);

// Get Product ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = $productObj->getProductById($product_id);

    if (!$product) {
        die("<p>Product not found!</p>");
    }
} else {
    die("<p>Invalid product ID!</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            text-align: center;
        }
        .product-detail {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            margin: auto;
        }
        h1 {
            font-size: 2em;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            padding: 10px;
            background: linear-gradient(135deg, rgb(255, 123, 0), rgb(179, 95, 0));
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }
        .product-detail img {
            width: 80%;
            max-width: 300px;
            height: auto;
            border-radius: 10px;
        }
        .product-detail h2 {
            font-size: 1.5em;
            color: #333;
            margin: 10px 0;
        }
        .product-detail p.price {
            font-weight: bold;
            color: #000;
            font-size: 1.2em;
        }
        .product-detail p.rating {
            color: #f39c12;
            font-size: 1.1em;
        }
        .product-detail p.description {
            font-size: 1em;
            color: #555;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1em;
            margin: 10px;
            color: white;
            text-align: center;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }
        .back-btn {
            background-color: rgb(255, 38, 0);
        }
        .back-btn:hover {
            background-color: rgb(179, 21, 0);
        }
        .buy-now-btn {
            background-color: rgb(167, 40, 46);
        }
        .buy-now-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Product Details</h1>
    <div class="product-detail">
        <img src="admin/uploads/<?php echo htmlspecialchars($product['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>">
        <h2><?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></h2>
        <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
        <p class="rating">⭐ <?php echo htmlspecialchars($product['rating'] ?? 'No rating', ENT_QUOTES); ?></p>
        <p class="description"><?php echo htmlspecialchars($product['description'], ENT_QUOTES); ?></p>
        
        <!-- Buy Now Button -->
        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES); ?>">
            <button type="submit" class="btn buy-now-btn">Buy Now</button>
        </form>

        <a href="index.php?p=shop" class="btn back-btn">Back to Shop</a>
    </div>
</div>

</body>
</html>
