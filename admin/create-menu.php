<?php


define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/create-menu.php

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = trim($_POST['label'] ?? '');
    $link_type = $_POST['link_type'] ?? 'page';
    $link_value = trim($_POST['link_value'] ?? '');

    if (!empty($label) && !empty($link_value)) {
        // Set paths
        $menus_dir = dirname(__DIR__) . '/menus/';

        // Create directory if missing
        if (!is_dir($menus_dir)) {
            mkdir($menus_dir, 0755, true);
        }

        // Get next available ID
        $files = glob($menus_dir . 'menu-*.json');
        $ids = array_map(function ($file) use ($menus_dir) {
            return (int) str_replace([$menus_dir . 'menu-', '.json'], '', $file);
        }, $files);
        $new_id = $ids ? max($ids) + 1 : 1;

        // Create menu data
        $menu_data = [
            'id' => $new_id,
            'label' => htmlspecialchars($label),
            'link_type' => htmlspecialchars($link_type),
            'link_value' => htmlspecialchars($link_value)
        ];

        // Save to JSON file
        $filename = $menus_dir . "menu-{$new_id}.json";
        file_put_contents($filename, json_encode($menu_data, JSON_PRETTY_PRINT));

        // Redirect to edit page
        header("Location: edit-menu.php?created={$new_id}");
        exit;
    } else {
        $error = "All required fields must be filled!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Menu Entry</title>
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

        input,
        select {
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
        <h1>Create New Menu Entry</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Menu Label *</label>
                <input type="text" name="label" required>
            </div>

            <div class="form-group">
                <label>Link Type *</label>
                <select name="link_type" required>
                    <option value="page">Page</option>
                    <option value="category">Category</option>
                    <option value="url">URL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Link Value *</label>
                <input type="text" name="link_value"
                    placeholder="Enter Page ID, Category ID, or full URL"
                    required>
                <small>
                    For Page/Category: Enter numeric ID<br>
                    For URL: Enter full URL (e.g. https://example.com)
                </small>
            </div>

            <button type="submit">Create Menu Entry</button>
            <a href="dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>