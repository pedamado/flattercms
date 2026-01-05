<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}


// admin/update-menu.php
$menus_dir = dirname(__DIR__) . '/menus/';
$menu_id = $_GET['id'] ?? null;
$menu_file = $menus_dir . "menu-{$menu_id}.json";

// Redirect if invalid ID
if (!$menu_id || !file_exists($menu_file)) {
    header('Location: edit-menu.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    // Verify authentication
    if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
        header('Location: index.php');
        exit;
    }

    // Validate and delete file
    $menu_id = $_GET['id'] ?? null;
    $menu_file = $menus_dir . "menu-{$menu_id}.json";

    if ($menu_id && file_exists($menu_file)) {
        unlink($menu_file);
        $_SESSION['message'] = "Menu entry #{$menu_id} deleted successfully";
        header('Location: edit-menu.php');
        exit;
    }
}

// Load existing data
$menu_data = json_decode(file_get_contents($menu_file), true);
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = trim($_POST['label'] ?? '');
    $link_type = $_POST['link_type'] ?? 'page';
    $link_value = trim($_POST['link_value'] ?? '');

    try {
        if (empty($label) || empty($link_value)) {
            throw new Exception("All required fields must be filled");
        }

        // Update menu data
        $menu_data['label'] = htmlspecialchars($label);
        $menu_data['link_type'] = htmlspecialchars($link_type);
        $menu_data['link_value'] = htmlspecialchars($link_value);

        // Save changes
        file_put_contents($menu_file, json_encode($menu_data, JSON_PRETTY_PRINT));
        $success = "Menu entry #{$menu_id} updated successfully!";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Menu Entry #<?= $menu_id ?></title>
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
        }

        .success {
            color: green;
        }

        .delete-link {
            color: #dc3545 !important;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Update Menu Entry #<?= $menu_id ?></h1>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Menu Label *</label>
                <input type="text" name="label"
                    value="<?= htmlspecialchars($menu_data['label']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Link Type *</label>
                <select name="link_type" required>
                    <option value="page" <?= $menu_data['link_type'] === 'page' ? 'selected' : '' ?>>Page</option>
                    <option value="category" <?= $menu_data['link_type'] === 'category' ? 'selected' : '' ?>>Category</option>
                    <option value="url" <?= $menu_data['link_type'] === 'url' ? 'selected' : '' ?>>URL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Link Value *</label>
                <input type="text" name="link_value"
                    value="<?= htmlspecialchars($menu_data['link_value']) ?>"
                    placeholder="Enter Page ID, Category ID, or full URL"
                    required>
            </div>

            <button type="submit">Update Menu Entry</button>
            <a href="edit-menu.php" style="margin-left: 10px;">Back to List</a>
            <a href="dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
            <!-- Add this in the form section -->
            <div style="margin-top: 20px;">
                <a href="update-menu.php?id=<?= $menu_id ?>&action=delete"
                    class="delete-link"
                    onclick="return confirm('Are you sure you want to delete this menu entry? This cannot be undone!')">
                    🗑 Delete This Menu Entry
                </a>
            </div>
        </form>
    </div>
</body>

</html>