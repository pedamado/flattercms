<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/admin-functions.php';


// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/update-page.php
$pages_dir = dirname(__DIR__) . '/pages/';
$page_id = $_GET['id'] ?? null;
$page_file = $pages_dir . "page-{$page_id}.json";

// Redirect if no ID provided
if (!$page_id || !file_exists($page_file)) {
    header('Location: edit-pages.php');
    exit;
}

// Load existing data
$page_data = json_decode(file_get_contents($page_file), true);
$error = '';
$success = '';

// Add at the top (before any output)
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    // Verify authentication
    if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
        header('Location: index.php');
        exit;
    }

    // Validate and delete file
    $page_id = $_GET['id'] ?? null;
    $page_file = $pages_dir . "page-{$page_id}.json";

    if ($page_id && file_exists($page_file)) {
        unlink($page_file);
        $_SESSION['message'] = "Page #{$page_id} deleted successfully";
        header('Location: edit-pages.php');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['title'])) throw new Exception("Title is required");
        if (empty($_POST['content'])) throw new Exception("Content is required");

        // // Update page data
        // $updated_data = [
        //     'id' => $page_id,
        //     'title' => htmlspecialchars($_POST['title']),
        //     'date' => htmlspecialchars($_POST['date']),
        //     'category' => htmlspecialchars($_POST['category']),
        //     'feature_image' => htmlspecialchars($_POST['feature_image']),
        //     'excerpt' => htmlspecialchars($_POST['excerpt']),
        //     'content' => htmlspecialchars($_POST['content'])
        // ];

        // In create-page.php and update-page.php
        $content = $_POST['content'] ?? '';
        $feature_image = $_POST['feature_image'] ?? '';

        // Convert relative paths to absolute URLs
        $content = str_replace('../media/', MEDIA_URL, $content);
        $feature_image = str_replace('../media/', MEDIA_URL, $feature_image);

        // Build page data
        $updated_data = [
            'id' => $page_id,
            'title' => htmlspecialchars($_POST['title'] ?? ''),
            'date' => htmlspecialchars($_POST['date'] ?? date('Y-m-d')),
            'category' => htmlspecialchars($_POST['category'] ?? ''),
            'feature_image' => htmlspecialchars($feature_image),
            'excerpt' => htmlspecialchars($_POST['excerpt'] ?? ''),
            // 'content' => htmlspecialchars($_POST['content'] ?? '')
            'content' => sanitize_page_content($content) // Use your sanitization function
        ];

        // Save updated file
        file_put_contents($page_file, json_encode($updated_data, JSON_PRETTY_PRINT));
        $success = "Page #{$page_id} updated successfully!";
        $page_data = $updated_data; // Update displayed data

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Page #<?= $page_id ?></title>
    <script src="tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink lists link image charmap preview anchor',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            height: 500,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>
    <!-- Rest of your styles -->
    <style>
        .form-container {
            max-width: 800px;
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
        textarea {
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
        <h1>Update Page #<?= $page_id ?></h1>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="<?= htmlspecialchars($page_data['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Date *</label>
                <input type="date" name="date" value="<?= htmlspecialchars($page_data['date']) ?>" required>
            </div>

            <div class="form-group">
                <label>Categories (comma separated)</label>
                <input type="text" name="category" value="<?= htmlspecialchars($page_data['category']) ?>">
            </div>

            <!-- <div class="form-group">
                <label>Feature Image URL</label>
                <input type="text" name="feature_image" value="<?= htmlspecialchars($page_data['feature_image']) ?>">
            </div> -->
            <div class="form-group">
                <label>Feature Image URL:</label>
                <input type="text" name="feature_image"
                    value="<?= htmlspecialchars($page_data['feature_image'] ?? '') ?>"
                    placeholder="<?= MEDIA_URL ?>filename.jpg">
                <small>Use full URL or <?= MEDIA_URL ?> path</small>
            </div>

            <div class="form-group">
                <label>Excerpt</label>
                <textarea name="excerpt" rows="3"><?= htmlspecialchars($page_data['excerpt']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Content *</label>
                <textarea id="editor" name="content" required>
                    <?= htmlspecialchars_decode($page_data['content'] ?? '') ?>
                </textarea>
            </div>

            <button type="submit">Update Page</button>
            <a href="edit-pages.php" style="margin-left: 10px;">Back to List</a>
            <!-- Add this in the form section -->
            <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                <a href="update-page.php?id=<?= $page_id ?>&action=delete"
                    class="delete-link"
                    onclick="return confirm('WARNING: This will permanently delete this page!\n\nAre you absolutely sure?')">
                    🗑 Permanently Delete This Page
                </a>
            </div>
        </form>
    </div>
</body>

</html>