<?php
session_start();

class Payment {
    private $totalAmount;

    public function __construct() {
        if (!isset($_SESSION['total_amount']) || $_SESSION['total_amount'] <= 0) {
            header("Location: checkout.php");
            exit;
        }
        $this->totalAmount = $_SESSION['total_amount'];
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }
}

$payment = new Payment();
$totalAmount = $payment->getTotalAmount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
        .payment-options {
            margin-top: 20px;
        }
        .payment-btn {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 10px;
            text-align: center;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }
        .paypal-btn {
            background-color: #ffc439;
        }
        .stripe-btn {
            background-color: #6772e5;
        }
    </style>
</head>
<body>
    <h1>Payment</h1>
    <p>Total Amount: <strong>$<?php echo number_format($totalAmount, 2); ?></strong></p>

    <div class="payment-options">
        <a href="paypal_payment.php?amount=<?php echo $totalAmount; ?>" class="payment-btn paypal-btn">Pay with PayPal</a>
        <a href="stripe_payment.php?amount=<?php echo $totalAmount; ?>" class="payment-btn stripe-btn">Pay with Stripe</a>
    </div>
</body>
</html>
