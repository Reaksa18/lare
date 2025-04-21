<?php
session_start();

class Database {
    private $host = "localhost";
    private $dbname = "phpproject";
    private $username = "root";
    private $password = "";
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}

class Cart {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function getCartCount() {
        return isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
    }

    public function addToCart($product_id) {
        $qty = 1;
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['qty'] += $qty;
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :product_id");
            $stmt->execute(['product_id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $product['price'],
                    'qty' => $qty
                ];
            }
        }
    }

    public function removeFromCart($product_id) {
        unset($_SESSION['cart'][$product_id]);
    }

    public function getCartItems() {
        return $_SESSION['cart'];
    }
}

$database = new Database();
$cart = new Cart($database->pdo);

// Handle AJAX requests
if (isset($_GET['get_cart_count'])) {
    echo $cart->getCartCount();
    exit;
}

if (isset($_GET['add_to_cart']) && isset($_GET['product_id'])) {
    $cart->addToCart($_GET['product_id']);
    exit;
}

if (isset($_GET['remove'])) {
    $cart->removeFromCart($_GET['remove']);
}

$cartItems = $cart->getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
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
            font-size: 16px;
        }

        td img {
            width: 50px;
            border-radius: 5px;
        }

        .remove-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }

        .cart-total {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .continue-shopping {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            margin-top: 20px;
        }

        .continue-shopping:hover {
            background-color: #218838;
        }
        
        .checkout-btn {
            display: inline-block;
            background-color: #ff9800;
            margin-left: 10px;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }

        .checkout-btn:hover {
            background-color: #e68900;
        }
    </style>
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (!empty($cartItems)): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($cartItems as $product_id => $product): ?>
                    <?php $subtotal = $product['price'] * $product['qty']; $total += $subtotal; ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><img src="admin/uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image"></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= $product['qty'] ?></td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td><a href="cart.php?remove=<?= $product_id ?>" class="remove-btn">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td colspan="2" class="cart-total">$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="index.php?p=shop" class="continue-shopping">Continue Shopping</a>
    <?php if (!empty($cartItems)): ?>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
</body>
</html>
