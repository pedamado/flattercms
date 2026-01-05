<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// Handle file deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $filename = basename($_GET['file'] ?? '');
    $file_path = dirname(__DIR__) . '/media/' . $filename;

    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            $_SESSION['message'] = "File '$filename' deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete file '$filename'";
        }
    }
    header('Location: media-library.php');
    exit;
}


// admin/media-library.php
$media_dir = dirname(__DIR__) . '/media/';
$files = [];
$sort = $_GET['sort'] ?? 'date';
$order = $_GET['order'] ?? 'desc';

// Get all media files
$media_files = glob($media_dir . '*.*');

foreach ($media_files as $file_path) {
    if (is_file($file_path)) {
        $filename = basename($file_path);
        $file_url = MEDIA_URL . $filename; // Use configured media URL

        // Extract date from filename or use filemtime
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $filename, $matches)) {
            $date = $matches[1];
        } else {
            $date = date('Y-m-d', filemtime($file_path));
        }

        // Clean filename (remove date prefix)
        $clean_name = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $filename);

        $files[] = [
            'date' => $date,
            'timestamp' => strtotime($date),
            'name' => $clean_name,
            'full_name' => $filename,
            'url' => $file_url, // Now uses full absolute URL
            'size' => filesize($file_path)
        ];
    }
}

// Sorting logic
usort($files, function ($a, $b) use ($sort, $order) {
    $compare = 0;

    if ($sort === 'date') {
        $compare = $a['timestamp'] <=> $b['timestamp'];
    } else {
        $compare = strcasecmp($a['name'], $b['name']);
    }

    return $order === 'asc' ? $compare : -$compare;
});

$new_order = $order === 'asc' ? 'desc' : 'asc';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Media Library</title>
    <style>
        .media-library {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        th:hover {
            background-color: #e0e0e0;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        .file-link {
            word-break: break-all;
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
    <div class="media-library">
        <h1>Media Library</h1>

        <div class="nav-buttons">
            <a href="upload-media.php" class="nav-button upload-button">Upload New File</a>
            <a href="dashboard.php" class="nav-button dashboard-button">Back to Dashboard</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="success" style="color:green; margin:10px 0; padding:10px; background:#f0fff0;">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error" style="color:red; margin:10px 0; padding:10px; background:#fff0f0;">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table>
            <!-- Modified table header with 3 columns -->
            <thead>
                <tr>
                    <th>
                        <a href="?sort=date&order=<?= $sort === 'date' ? $new_order : 'desc' ?>">
                            Date <?= $sort === 'date' ? ($order === 'asc' ? '↑' : '↓') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=name&order=<?= $sort === 'name' ? $new_order : 'desc' ?>">
                            File Name <?= $sort === 'name' ? ($order === 'asc' ? '↑' : '↓') : '' ?>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($file['date']) ?></td>
                        <td class="file-link">
                            <a href="<?= htmlspecialchars($file['url']) ?>" target="_blank">
                                <?= htmlspecialchars($file['name']) ?>
                            </a>
                            <small>(<?= round($file['size'] / 1024, 1) ?> KB)</small>
                        </td>
                        <td>
                            <a href="media-library.php?action=delete&file=<?= urlencode($file['full_name']) ?>"
                                class="delete-link"
                                onclick="return confirm('Permanently delete <?= htmlspecialchars($file['name']) ?>?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>