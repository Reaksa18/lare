<?php
session_start();

// Require the Stripe PHP SDK (make sure you have installed it via Composer)
require_once 'vendor/autoload.php';

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('your_stripe_secret_key');

// Check if there's a valid total amount in the session
if (!isset($_SESSION['total_amount']) || $_SESSION['total_amount'] <= 0) {
    header("Location: checkout.php");
    exit;
}

$total_amount = $_SESSION['total_amount'] * 100; // Convert to cents for Stripe

// Handle the POST request to create a Stripe checkout session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create a Checkout Session
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Purchase Items',
                        ],
                        'unit_amount' => $total_amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'http://yourdomain.com/stripe_payment.php?status=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://yourdomain.com/stripe_payment.php?status=cancel',
        ]);
        
        // Redirect to the Stripe Checkout page
        header("Location: " . $checkoutSession->url);
        exit;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
    }
}
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
        .stripe-btn {
            background-color: #6772e5;
        }
    </style>
</head>
<body>
    <h1>Payment</h1>
    <p>Total Amount: <strong>$<?php echo number_format($_SESSION['total_amount'], 2); ?></strong></p>

    <div class="payment-options">
        <form action="" method="POST">
            <button type="submit" class="payment-btn stripe-btn">Pay with Stripe</button>
        </form>
    </div>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo "<p>Payment successful! Thank you for your purchase.</p>";
        } elseif ($_GET['status'] == 'cancel') {
            echo "<p>Payment was canceled. Please try again.</p>";
        }
    }
    ?>
</body>
</html>
