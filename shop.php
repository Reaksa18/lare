<?php

// Database connection using PDO (OOP)
class Database {
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
}

// Create database connection
$database = new Database();
$conn = $database->conn;

// If the "Add to Cart" button is clicked
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Add product ID to the cart session
    $_SESSION['cart'][] = $productId;
}

// Query to fetch products (using PDO)
$sql = "SELECT * FROM products";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Fetch the results
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "include/head.php"; ?>
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
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .product-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }
        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .product h2 {
            font-size: 1.2em;
            color: #333;
        }
        .product p.price {
            font-weight: bold;
            color: #000;
        }
        .product button {
            background-color: rgb(247, 79, 13);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin: 5px;
        }
        .product button:hover {
            background-color: #218838;
        }
        .product .view-details {
            background-color:rgb(253, 8, 8);
        }
        .product .view-details:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <section class="inner_page_head">
        <div class="container_fuild">
            <div class="row">
                <div class="col-md-12">
                    <div class="full">
                        <h3>Product Grid</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container">
        <h1>Welcome To Our Shop</h1>
    </div>

    <div class="product-container">
        <?php
        if ($stmt->rowCount() > 0) {
            foreach ($products as $row) {
                echo "<div class='product'>";
                echo "<img src='admin/uploads/" . htmlspecialchars($row['image'], ENT_QUOTES) . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES) . "'>";
                echo "<h2>" . htmlspecialchars($row['name'], ENT_QUOTES) . "</h2>";
                echo "<p class='price'>$" . number_format($row['price'], 2) . "</p>";
                
                // Buttons
                echo "<form method='POST' action='product_details.php'>";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES) . "'>";
                echo "<button type='submit' class='view-details'>View Details</button>";
                echo "</form>";

                echo "<form method='POST'>";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES) . "'>";
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
    <?php include "include/footer.php"?>
</body>
</html>
