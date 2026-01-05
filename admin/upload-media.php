<?php


define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/upload-media.php

$media_dir = dirname(__DIR__) . '/media/';
$max_upload = ini_get('upload_max_filesize');
$error = '';
$success = '';

// Create media directory if missing
if (!is_dir($media_dir)) {
    mkdir($media_dir, 0755, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $file = $_FILES['media_file'];

    try {
        // Validate file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Get file parts
        $original_name = $file['name'];
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $base_name = pathinfo($original_name, PATHINFO_FILENAME);

        // Sanitize filename
        $clean_name = preg_replace('/[^a-zA-Z0-9]/', '-', $base_name); // Replace special chars
        $clean_name = str_replace([' ', '.', '_'], '-', $clean_name); // Replace spaces and dots
        $clean_name = preg_replace('/-+/', '-', $clean_name); // Remove duplicate dashes
        $clean_name = trim($clean_name, '-'); // Trim dashes from ends
        $clean_name = strtolower($clean_name); // Convert to lowercase

        // If name is empty after sanitization, use random string
        if (empty($clean_name)) {
            $clean_name = substr(md5(time()), 0, 8);
        }

        // Add date prefix and construct final filename
        $date_prefix = date('Y-m-d');
        $final_filename = "{$date_prefix}-{$clean_name}.{$extension}";
        $target_path = $media_dir . $final_filename;

        // Ensure unique filename
        $counter = 1;
        while (file_exists($target_path)) {
            $final_filename = "{$date_prefix}-{$clean_name}-{$counter}.{$extension}";
            $target_path = $media_dir . $final_filename;
            $counter++;
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            throw new Exception('Failed to save uploaded file');
        }

        $success = "File uploaded as: {$final_filename}";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upload Media</title>
    <style>
        .upload-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            width: 100%;
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
            margin: 10px 0;
        }

        .success {
            color: green;
            margin: 10px 0;
        }

        .limits {
            color: #666;
            margin: 15px 0;
        }

        .filename-example {
            background: #f5f5f5;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="upload-container">
        <h1>Upload Media Files</h1>

        <div class="limits">
            <strong>Upload Requirements:</strong><br>
            • Maximum file size: <?= $max_upload ?>B<br>
            • Files are automatically renamed<br>
            • Allowed characters: a-z, 0-9, and hyphens<br>
            • Date prefix added automatically
        </div>

        <div class="filename-example">
            <strong>Example:</strong><br>
            Original: "Vacation Photo 2023.jpg"<br>
            Becomes: "2023-10-05-vacation-photo-2023.jpg"
        </div>

        <?php if ($error): ?>
            <div class="error">Error: <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select file to upload:</label>
                <input type="file" name="media_file" required>
            </div>

            <button type="submit">Upload File</button>
            <a href="media-library.php" style="margin-left: 10px;">View Media Library</a>
            <a href="dashboard.php" style="margin-left: 10px;">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>