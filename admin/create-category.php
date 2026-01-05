<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/create-category.php

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['name'] ?? '');

    if (!empty($category_name)) {
        // Set paths
        $categories_dir = dirname(__DIR__) . '/categories/';

        // Create directory if missing
        if (!is_dir($categories_dir)) {
            mkdir($categories_dir, 0755, true);
        }

        // Get next available ID
        $files = glob($categories_dir . 'category-*.json');
        $ids = array_map(function ($file) use ($categories_dir) {
            return (int) str_replace([$categories_dir . 'category-', '.json'], '', $file);
        }, $files);
        $new_id = $ids ? max($ids) + 1 : 1;

        // Create category data
        $category_data = [
            'id' => $new_id,
            'name' => htmlspecialchars($category_name)
        ];

        // Save to JSON file
        $filename = $categories_dir . "category-{$new_id}.json";
        file_put_contents($filename, json_encode($category_data, JSON_PRETTY_PRINT));

        // Redirect to edit page
        header("Location: edit-categories.php?created={$new_id}");
        exit;
    } else {
        $error = "Category name cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Category</title>
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
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Create New Category</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Category Name:</label>
                <input type="text" name="name" required>
            </div>

            <button type="submit">Create Category</button>
            <a href="dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>