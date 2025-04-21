<?php
session_start();
include('db.php');  // Include the database connection

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute SQL to fetch the user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];  // Store user ID in session
        header('Location: index.php');  // Redirect to dashboard
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid email or password';  // Set error message
        header('Location: login.php');  // Redirect back to login page
        exit;
    }
}
?>
