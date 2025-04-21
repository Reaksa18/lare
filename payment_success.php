<?php
session_start();
include_once 'lib/db.php';
// Clear session
unset($_SESSION['cart']);
unset($_SESSION['customer']);
unset($_SESSION['total_price']);

// Redirect to cart.php to reflect the changes
header('Location: cart.php');
exit;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['payment_details'])) {
    $order_id = $_POST['order_id'];
    $payment_details = $_POST['payment_details'];  // This contains PayPal transaction details
    $transaction_id = $payment_details['id']; // Assuming PayPal provides a transaction ID

    // Check if customer session exists
    if (!isset($_SESSION['customer'])) {
        echo json_encode(['error' => 'Customer details missing.']);
        exit;
    }

    $customer = $_SESSION['customer'];
    $total_price = $_SESSION['total_price'];

    try {
        $pdo = Database::getInstance()->conn;

        // Insert customer details into the `customers` table
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, address, phone, total_price, transaction_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $customer['name'],
            $customer['email'],
            $customer['address'],
            $customer['phone'],
            $total_price,
            $transaction_id
        ]);
        $customer_id = $pdo->lastInsertId();

        // Update the order with payment details
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'Paid', transaction_id = ? WHERE id = ?");
        $stmt->execute([$transaction_id, $order_id]);

        // Clear session
        unset($_SESSION['cart']);
        unset($_SESSION['customer']);
        unset($_SESSION['total_price']);

        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <?php include('./include/head.php'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .success-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .success-container h1 {
            color: #28a745;
        }
        .success-container p {
            font-size: 18px;
            color: #333;
        }
        .go-home-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            margin-top: 20px;
        }
        .go-home-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <h1>Payment Successful!</h1>
        <p>Your order has been successfully processed. Thank you for your purchase.</p>
        <a href="index.php" class="go-home-btn">Go Back Home</a>
    </div>

</body>
</html>
