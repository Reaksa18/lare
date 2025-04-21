<?php
include_once './Libb/library.php';

// Usage
$database = new Databases();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['username']) && !empty($_POST['password'])) {
    $message = $user->register(trim($_POST['username']), trim($_POST['password']));

    if ($message === "Registration successful!") {
        echo "<script>alert('$message'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('$message');</script>";
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<script>alert('Please fill in all fields.');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <?php include('./include/head.php'); ?>
</head>
<style>
    .conten{
        width: 700px;
        margin-left:300px;
    }
</style>
<body>
    <?php include('./include/header.php'); ?>
    <!-- inner page section -->
    <section class="inner_page_head">
         <div class="container_fuild">
            <div class="row">
               <div class="col-md-12">
                  <div class="full">
                     <h3>Register</h3>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- end inner page section -->
<div class="conten">
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <button type="submit" name="register">Register</button>
    </form>
</div>
    <?php include('./include/footer.php'); ?>
</body>
</html>