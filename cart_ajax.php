<?php

// Handle getting cart count
if (isset($_POST['action']) && $_POST['action'] == 'get_cart') {
    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    echo json_encode(['cart_count' => $cart_count]);
}
// Add product to the cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Get product ID from request
    $productId = $_POST['product_id'];

    // Check if product already exists in cart
    $exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            $_SESSION['cart'][$key]['quantity'] += 1;  // Increase quantity if product exists
            $exists = true;
            break;
        }
    }

    // If product doesn't exist, add to the cart
    if (!$exists) {
        // You can fetch product details like name, price, etc., from the database
        $conn = new PDO("mysql:host=localhost;dbname=phpproject", "root", "");

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    // Return cart count
    $cart_count = count($_SESSION['cart']);
    echo json_encode(['cart_count' => $cart_count]);
}
