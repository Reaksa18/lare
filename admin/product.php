<?php
ob_start();  // Start output buffering
session_start();  // Start session if required

// Database Connection using PDO
class Database {
    private $host = "localhost";
    private $db_name = "phpproject";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Connection failed: " . $exception->getMessage());
        }
    }
}

$db = new Database();
$conn = $db->conn;

// Ensure uploads directory exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Add Product
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name']; // Unique filename
        $target_path = 'uploads/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = $image_name; // Save only the filename in the database
        }
    }

    // Insert product into the database using PDO
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);

    // Redirect after inserting
    header("Location: product.php");
    exit();  // Always use exit after header redirect
}

// Update Product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $image_query = "";
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name']; // Unique filename
        $target_path = 'uploads/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Get old image name using PDO
            $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $old_image = $stmt->fetch(PDO::FETCH_ASSOC)['image'];

            // Delete old image file
            if ($old_image && file_exists('uploads/' . $old_image)) {
                unlink('uploads/' . $old_image);
            }

            $image_query = ", image = '$image_name'"; // Save only filename
        }
    }

    // Update product using PDO
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? $image_query WHERE id = ?");
    $stmt->execute([$name, $price, $description, $id]);

    // Redirect after updating
    header("Location: product.php");
    exit();  // Always use exit after header redirect
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete image file using PDO
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product['image'] && file_exists('uploads/' . $product['image'])) {
        unlink('uploads/' . $product['image']);
    }

    // Delete product using PDO
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect after deleting
    header("Location: product.php");
    exit();  // Always use exit after header redirect
}

// Fetch Product for Editing
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "include/head.php" ?>

<body>

<div class="container">
    <div class="page-inner">
        <h1>Product Management</h1>

        <h2><?php echo isset($edit) ? "Edit Product" : "Add Product"; ?></h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
            <input type="text" name="name" placeholder="Product Name" value="<?php echo $edit['name'] ?? ''; ?>" required>
            <input type="number" name="price" placeholder="Price" value="<?php echo $edit['price'] ?? ''; ?>" required>
            <textarea name="description" placeholder="Description"><?php echo $edit['description'] ?? ''; ?></textarea>
            
            <input type="file" name="image">
            <?php if (isset($edit) && $edit['image']): ?>
                <img src="uploads/<?php echo $edit['image']; ?>" width="100" alt="Product Image">
            <?php endif; ?>

            <button type="submit" name="<?php echo isset($edit) ? 'update' : 'add'; ?>">
                <?php echo isset($edit) ? "Update" : "Add"; ?>
            </button>
            <?php if (isset($edit)) echo '<a href="product.php">Cancel</a>'; ?>
        </form>

        <h2>Product List</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT * FROM products");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row):
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <?php if ($row['image']): ?>
                            <img src="uploads/<?php echo $row['image']; ?>" width="50" alt="Product Image">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="product.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="product.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</div>

</body>
</html>
