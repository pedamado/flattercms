<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Add at the very top (before any output)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    // Verify authentication
    if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
        header('Location: index.php');
        exit;
    }

    // Validate category ID
    $category_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $category_file = dirname(__DIR__) . '/categories/category-' . $category_id . '.json';

    if ($category_id && file_exists($category_file)) {
        // Create backup
        $backup_dir = dirname(__DIR__) . '/backups/categories/';
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }
        copy($category_file, $backup_dir . 'category-' . $category_id . '-' . date('YmdHis') . '.json');

        // Perform deletion
        if (unlink($category_file)) {
            $_SESSION['message'] = "Category #$category_id deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete category #$category_id";
        }

        header('Location: edit-categories.php');
        exit;
    }
}

// admin/update-category.php
$categories_dir = dirname(__DIR__) . '/categories/';
$category_id = $_GET['id'] ?? null;
$category_file = $categories_dir . "category-{$category_id}.json";

// Redirect if invalid ID
if (!$category_id || !file_exists($category_file)) {
    header('Location: edit-categories.php');
    exit;
}

// Load existing data
$category_data = json_decode(file_get_contents($category_file), true);
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name'] ?? '');

    try {
        if (empty($new_name)) {
            throw new Exception("Category name cannot be empty");
        }

        // Update category data
        $category_data['name'] = htmlspecialchars($new_name);

        // Save changes
        file_put_contents($category_file, json_encode($category_data, JSON_PRETTY_PRINT));
        $success = "Category #{$category_id} updated successfully!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Category #<?= $category_id ?></title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        .delete-link {
            color: #dc3545 !important;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Update Category #<?= $category_id ?></h1>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Category Name:</label>
                <input type="text" name="name"
                    value="<?= htmlspecialchars($category_data['name']) ?>"
                    required>
            </div>

            <button type="submit">Update Category</button>
            <a href="edit-categories.php" style="margin-left: 10px;">Back to List</a>
            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                <a href="update-category.php?id=<?= htmlspecialchars($category_id) ?>&action=delete&token=<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>"
                    class="delete-link"
                    onclick="return confirm('⚠️ WARNING: Deleting this category will affect all associated pages!\n\nAre you absolutely sure?')">
                    🗑 Permanently Delete This Category
                </a>
            </div>
        </form>

    </div>
</body>

</html>