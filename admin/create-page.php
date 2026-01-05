<?php
define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/admin-functions.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// create-page.php (in admin folder)

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set paths relative to admin folder's parent
    $pages_dir = dirname(__DIR__) . '/pages/';

    // Create pages directory if missing
    if (!is_dir($pages_dir)) {
        mkdir($pages_dir, 0755, true);
    }

    // Get next available ID
    $files = glob($pages_dir . 'page-*.json');
    $ids = array_map(function ($file) use ($pages_dir) {
        return (int) str_replace([$pages_dir . 'page-', '.json'], '', $file);
    }, $files);
    $new_id = $ids ? max($ids) + 1 : 1;

    // In create-page.php and update-page.php
    $content = $_POST['content'] ?? '';
    $feature_image = $_POST['feature_image'] ?? '';

    // Convert relative paths to absolute URLs
    $content = str_replace('../media/', MEDIA_URL, $content);
    $feature_image = str_replace('../media/', MEDIA_URL, $feature_image);

    // Build page data
    $page_data = [
        'id' => $new_id,
        'title' => htmlspecialchars($_POST['title'] ?? ''),
        'date' => htmlspecialchars($_POST['date'] ?? date('Y-m-d')),
        'category' => htmlspecialchars($_POST['category'] ?? ''),
        'feature_image' => htmlspecialchars($feature_image),
        'excerpt' => htmlspecialchars($_POST['excerpt'] ?? ''),
        // 'content' => htmlspecialchars($_POST['content'] ?? '')
        'content' => sanitize_page_content($content) // Use your sanitization function
    ];

    // Save to JSON file
    $filename = $pages_dir . "page-{$new_id}.json";
    file_put_contents($filename, json_encode($page_data, JSON_PRETTY_PRINT));

    // Redirect to edit page
    header("Location: edit-pages.php?created={$new_id}");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Page</title>
    <script src="tinymce/tinymce.min.js"></script>
    <!-- <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink lists link image charmap preview anchor',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            height: 500,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script> -->

    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink lists link image charmap preview anchor',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            height: 500,
            relative_urls: false, // Disable relative URLs
            remove_script_host: false, // Keep full URLs
            document_base_url: "<?= BASE_URL ?>", // Set base URL
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
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Create New Page</h1>

        <form method="POST">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label>Categories (comma separated):</label>
                <input type="text" name="category">
            </div>

            <!-- <div class="form-group">
                <label>Feature Image URL:</label>
                <input type="text" name="feature_image">
            </div> -->

            <div class="form-group">
                <label>Feature Image URL:</label>
                <input type="text" name="feature_image">
                <small>Use full URL or <?= MEDIA_URL ?> path</small>
            </div>


            <div class="form-group">
                <label>Excerpt:</label>
                <textarea name="excerpt" rows="3"></textarea>
            </div>

            <!-- <div class="form-group">
                <label>Content:</label>
                <textarea name="content" rows="10" required></textarea>
            </div> -->

            <div class="form-group">
                <label>Content *</label>
                <textarea id="editor" name="content" required>
                    <?= htmlspecialchars_decode($page_data['content'] ?? '') ?>
                </textarea>
            </div>

            <button type="submit">Create Page</button>
        </form>
    </div>
</body>

</html>