<?php
session_start();

// Destroy the session to log the user out
session_unset();
session_destroy();

// Redirect to home.php after logout
header('Location: index.php');
exit;
?>
