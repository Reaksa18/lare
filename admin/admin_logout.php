<?php
session_start();
include '../lib/db.php';

if (isset($_SESSION['admin_token'])) {
    $stmt = $pdo->prepare("UPDATE admin SET token = NULL, token_expiry = NULL WHERE token = ?");
    $stmt->execute([$_SESSION['admin_token']]);
}

session_destroy();
header("Location: admin_login.php");
exit;
?>
