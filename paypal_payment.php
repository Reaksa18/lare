<?php
session_start();

class Database {
    private $host = 'localhost';
    private $dbname = 'phpproject';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}

class Order {
    private $pdo;
    private $total_amount;
    private $transaction_id;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
        $this->total_amount = $_SESSION['total_amount'] ?? 0;
        $this->transaction_id = $_GET['transaction_id'] ?? null;
    }

    public function processPayment() {
        if (!isset($_SESSION['total_amount']) || $_SESSION['total_amount'] <= 0) {
            header("Location: payment.php");
            exit;
        }

        // Clear cart after successful payment
        if ($this->transaction_id) {
            unset($_SESSION['cart']);
            unset($_SESSION['total_amount']);
        }
    }

    public function getTotalAmount() {
        return $this->total_amount;
    }

    public function getTransactionId() {
        return $this->transaction_id;
    }
}

$order = new Order();
$order->processPayment();
$total_amount = $order->getTotalAmount();
$transaction_id = $order->getTransactionId();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AfJg6GlBTDMpqbhbbOOXKJ_aup55rzxuWXPJxpbPjQHLUk0EvGnLYSOzoMCzz852pTukbPs7JTowlOAv&currency=USD&components=buttons,funding-eligibility"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: <?php echo $transaction_id ? '#28a745' : '#007bff'; ?>;
        }
        .payment-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 60vh;
        }
        .paypal-buttons {
            width: 300px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <?php if ($transaction_id): ?>
        <h1>Payment Successful</h1>
        <p>Transaction ID: <strong><?php echo htmlspecialchars($transaction_id); ?></strong></p>
        <p>Thank you for your purchase!</p>
        <a href="index.php" class="back-btn">Go to Homepage</a>
    <?php else: ?>
        <h1>Pay with PayPal</h1>
        <p>Total Amount: <strong>$<?php echo number_format($total_amount, 2); ?></strong></p>

        <div id="paypal-button-container" class="paypal-buttons"></div>

        <script>
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'pay'
                },
                fundingSource: paypal.FUNDING.PAYPAL,
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo $total_amount; ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert('Transaction completed by ' + details.payer.name.given_name);
                        window.location.href = 'paypal_payment.php?transaction_id=' + details.id;
                    });
                },
                onError: function(err) {
                    alert('Something went wrong. Please try again.');
                }
            }).render('#paypal-button-container');
        </script>
    <?php endif; ?>
</div>

</body>
</html>
