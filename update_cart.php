<?php
session_start();

// Initialize cart if it doesn’t exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get product ID from AJAX request
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Increase quantity if product exists in cart, otherwise set it to 1
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Return updated cart count (sum of all item quantities)
    echo array_sum($_SESSION['cart']);
}
?>
