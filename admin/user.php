<?php
// Database connection settings
$host = 'localhost'; 
$user = 'root'; 
$pass = ''; 
$dbname = 'phpproject'; 
try {
    // Create a PDO instance for MySQL connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable exceptions for errors
} catch (PDOException $e) {
    // If connection fails, show an error message
    die("Connection failed: " . $e->getMessage());
}

// Check if delete action is triggered (via AJAX)
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    // SQL query to delete the user from the database
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$delete_id]);

    // Respond with success message
    echo 'success';
    exit();
}

// Fetch users data from the database
$query = "SELECT id, username, password, token, token_expiry FROM users";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users</title>
    <style>
         body, h2, table, td, th {
            margin: 0;
            padding: 0;
        }
        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        /* Container styling */
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Link Styling */
        a {
            color: #e74c3c;
            font-size: 14px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Empty Row Styling */
        .empty-row {
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        /* Delete Button Styling */
        td a {
            font-size: 12px;
            padding: 6px 10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
        }

        td a:hover {
            background-color: #c0392b;
        }

        /* Ensure buttons and links don't stretch */
        td a {
            white-space: nowrap;
        }

        /* Column Width and Overflow Handling */
        td {
            max-width: 120px; /* Limit the width of the columns */
            white-space: nowrap; /* Prevent text from wrapping */
            overflow: hidden; /* Hide overflowed text */
            text-overflow: ellipsis; /* Add ellipsis when text overflows */
        }

        td a {
            font-size: 12px;
            padding: 6px 10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 480px) {
            .container {
                width: 100%;
                padding: 15px;
            }
            table {
                font-size: 12px;
            }

            th, td {
                padding: 8px;
            }

            h2 {
                font-size: 16px;
            }

            a {
                font-size: 12px;
            }

            td {
                max-width: 100px; /* Further limit column width on small screens */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Users Management</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Token</th>
                <th>Token Expiry</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if users exist
            if ($users) {
                // Loop through the users and display them in rows
                foreach ($users as $user) {
                    echo "<tr id='user_{$user['id']}'>
                            <td>{$user['id']}</td>
                            <td>{$user['username']}</td>
                            <td>{$user['password']}</td>
                            <td>{$user['token']}</td>
                            <td>{$user['token_expiry']}</td>
                            <td><a href='#' class='delete-btn' data-id='{$user['id']}'>Delete</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='empty-row'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    // JavaScript function to handle delete via AJAX
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();  // Prevent the default link action
            const userId = this.getAttribute('data-id');

            if (confirm('Are you sure you want to delete this user?')) {
                // AJAX request to delete the user
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);  // Post request to the current page
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === 'success') {
                            // Remove the row from the table
                            document.getElementById('user_' + userId).remove();
                        } else {
                            alert('Error deleting user.');
                        }
                    }
                };
                xhr.send('delete_id=' + userId);
            }
        });
    });
</script>

</body>
</html>