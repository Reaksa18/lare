<?php
session_start();

class Database {
    private $host = 'localhost';
    private $dbname = 'phpproject';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Could not connect to the database: " . $e->getMessage());
        }
    }
}

class Checkout {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function calculateTotal() {
        $total_amount = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product) {
                $total_amount += $product['price'] * $product['qty'];
            }
        }
        return $total_amount;
    }

    public function confirmPurchase() {
        if (!empty($_SESSION['cart'])) {
            $_SESSION['total_amount'] = $this->calculateTotal();
            header("Location: payment.php");
            exit;
        }
    }
}

$db = new Database();
$checkout = new Checkout($db->conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_purchase'])) {
    $checkout->confirmPurchase();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .checkout-btn, .back-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin: 10px;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        .back-btn {
            display: inline-block;
            background-color: #dc3545;
            margin-left: 10px;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Checkout</h1>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION['cart'] as $product): ?>
                    <?php $subtotal = $product['price'] * $product['qty']; ?>
                    <?php $total += $subtotal; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><img src="admin/uploads/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['qty']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <form method="post">
            <button type="submit" name="confirm_purchase" class="checkout-btn">Confirm Purchase</button>
        </form>
        <a href="cart.php" class="back-btn">Go Back to Cart</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>
