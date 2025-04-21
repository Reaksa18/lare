<?php
// slideshow.php - Admin Panel for uploading, editing, and deleting slides
include('db.php'); // Database connection

// Handle Add New Slide (AJAX request)
if (isset($_POST['action']) && $_POST['action'] == 'add_slide') {
    if ($_FILES['background_image']['error'] != 0) {
        echo "Error uploading file: " . $_FILES['background_image']['error'];
    } else {
        $imageName = $_FILES['background_image']['name'];
        $imageTmp = $_FILES['background_image']['tmp_name'];
        $uploadDir = 'slidepic/';
        $imagePath = $uploadDir . basename($imageName);

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $description = $_POST['description'];

            $sql = "INSERT INTO slides (description, background_image) VALUES (:description, :imagePath)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':description' => $description,
                ':imagePath' => $imagePath
            ]);
            echo "Slide added successfully!";
        } else {
            echo "Failed to upload image.";
        }
    }
    exit();
}

// Handle Edit Slide (AJAX request)
if (isset($_POST['action']) && $_POST['action'] == 'edit_slide') {
    $edit_id = $_POST['edit_id'];

    if ($_FILES['background_image']['error'] != 0) {
        echo "Error uploading file: " . $_FILES['background_image']['error'];
    } else {
        $imageName = $_FILES['background_image']['name'];
        $imageTmp = $_FILES['background_image']['tmp_name'];
        $uploadDir = 'slidepic/';
        $imagePath = $uploadDir . basename($imageName);

        if (move_uploaded_file($imageTmp, $imagePath)) {
            $description = $_POST['description'];

            $sql = "UPDATE slides SET description = :description, background_image = :imagePath WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':description' => $description,
                ':imagePath' => $imagePath,
                ':id' => $edit_id
            ]);

            echo "Slide updated successfully!";
        } else {
            echo "Failed to upload image.";
        }
    }
    exit();
}

// Handle Delete Slide (AJAX request)
if (isset($_POST['action']) && $_POST['action'] == 'delete_slide') {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare("SELECT background_image FROM slides WHERE id = :id");
    $stmt->execute([':id' => $delete_id]);
    $slide = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($slide) {
        if (file_exists($slide['background_image'])) {
            if (unlink($slide['background_image'])) {
                echo "Image file deleted successfully.\n";
            } else {
                echo "Failed to delete image file.\n";
            }
        } else {
            echo "Image file not found.\n";
        }

        $stmt = $pdo->prepare("DELETE FROM slides WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);

        echo "Slide deleted successfully!";
    } else {
        echo "Slide not found!";
    }
    exit();
}

// Fetch existing slides for display
$stmt = $pdo->query("SELECT * FROM slides");
$slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Slides</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
<div class="page-inner">
    <h1>Manage Slides</h1>

    <!-- Add Slide Form -->
    <h2>Add New Slide</h2>
    <form id="addSlideForm" enctype="multipart/form-data">
        <label for="description">Slide Description:</label>
        <input type="text" name="description" id="description" required><br><br>

        <label for="background_image">Upload Background Image:</label>
        <input type="file" name="background_image" id="background_image" required><br><br>

        <button type="submit">Add Slide</button>
    </form>

    <hr>

    <!-- Edit Slide Form (initially hidden) -->
    <form id="editSlideForm" enctype="multipart/form-data" style="display: none;">
        <input type="hidden" name="edit_id" id="edit_id">
        <label for="edit_description">Slide Description:</label>
        <input type="text" name="description" id="edit_description" required><br><br>

        <label for="edit_background_image">Upload Background Image (optional):</label>
        <input type="file" name="background_image" id="edit_background_image"><br><br>

        <button type="submit">Update Slide</button>
    </form>

    <hr>

    <!-- Display List of Slides -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Background Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($slides as $row): ?>
        <tr id="slide_<?= $row['id'] ?>">
            <td><?= $row['id'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><img src="<?= $row['background_image'] ?>" alt="Slide Image" width="100"></td>
            <td>
                <button class="editSlideBtn" data-id="<?= $row['id'] ?>" data-description="<?= $row['description'] ?>" data-image="<?= $row['background_image'] ?>">Edit</button> |
                <button class="deleteSlideBtn" data-id="<?= $row['id'] ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div></div>

<script>
    // Add Slide
    $('#addSlideForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'add_slide');

        $.ajax({
            url: 'slideshow.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                location.reload(); // Reload to show the new slide
            }
        });
    });

    // Edit Slide
    $('.editSlideBtn').on('click', function() {
        var id = $(this).data('id');
        var description = $(this).data('description');
        var image = $(this).data('image');

        $('#edit_id').val(id);
        $('#edit_description').val(description);
        $('#editSlideForm').show();
    });

    $('#editSlideForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'edit_slide');

        $.ajax({
            url: 'slideshow.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response);
                location.reload(); // Reload to show updated slide
            }
        });
    });

    // Delete Slide
    $('.deleteSlideBtn').on('click', function() {
        var id = $(this).data('id');

        if (confirm('Are you sure you want to delete this slide?')) {
            $.ajax({
                url: 'slideshow.php',
                type: 'POST',
                data: { action: 'delete_slide', delete_id: id },
                success: function(response) {
                    alert(response);
                    $('#slide_' + id).remove(); // Remove the deleted slide from the table
                }
            });
        }
    });
</script>

</body>
</html>
