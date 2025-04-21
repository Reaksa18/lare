<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <style>
        /* Internal CSS Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            color: white;
            margin-bottom: 20px;
        }
        .alert.success { background-color: #4CAF50; }
        .alert.error { background-color: #f44336; }
        .toggle-btn {
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        .toggle-btn:hover {
            background-color: #007bb5;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 id="formTitle">Login</h2>

    <!-- Login Form -->
    <div id="loginForm" class="form-section active">
        <form action="login_process.php" method="POST">
            <input type="email" name="email" id="loginEmail" placeholder="Email" required>
            <input type="password" name="password" id="loginPassword" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert error">
                <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="form-section">
        <form action="register_process.php" method="POST">
            <input type="text" name="username" id="registerUsername" placeholder="Username" required>
            <input type="email" name="email" id="registerEmail" placeholder="Email" required>
            <input type="password" name="password" id="registerPassword" placeholder="Password" required>
            <input type="password" name="confirmPassword" id="registerConfirmPassword" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Register</button>
        </form>
        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="alert error">
                <?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['register_success'])): ?>
            <div class="alert success">
                <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Toggle Button -->
    <button class="toggle-btn" id="toggleFormBtn">Don't have an account? Register here.</button>
</div>

<script>
    // Toggle between Login and Register Forms
    document.getElementById('toggleFormBtn').addEventListener('click', function() {
        var loginForm = document.getElementById('loginForm');
        var registerForm = document.getElementById('registerForm');
        var formTitle = document.getElementById('formTitle');
        
        // Toggle visibility of forms
        if (loginForm.classList.contains('active')) {
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
            formTitle.textContent = 'Register';
            this.textContent = 'Already have an account? Login here.';
        } else {
            registerForm.classList.remove('active');
            loginForm.classList.add('active');
            formTitle.textContent = 'Login';
            this.textContent = 'Don\'t have an account? Register here.';
        }
    });
</script>

</body>
</html>
