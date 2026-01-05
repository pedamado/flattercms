<?php
define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/edit-pages.php
$pages_dir = dirname(__DIR__) . '/pages/';
$pages = [];

// Get all page files
$files = glob($pages_dir . 'page-*.json');

// Load and parse pages
foreach ($files as $file) {
    $content = file_get_contents($file);
    $pages[] = json_decode($content, true);
}

// Sorting logic
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

usort($pages, function ($a, $b) use ($sort, $order) {
    $valueA = $a[$sort];
    $valueB = $b[$sort];

    if ($sort === 'date') {
        $valueA = strtotime($valueA);
        $valueB = strtotime($valueB);
    }

    if ($order === 'asc') {
        return $valueA <=> $valueB;
    }
    return $valueB <=> $valueA;
});

// Toggle order for next click
$new_order = $order === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Pages</title>
    <style>
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
    </style>
</head>

<body>
    <h1>Manage Pages</h1>

    <?php if (!empty($_GET['created'])): ?>
        <div style="color: green; margin: 10px 0;">
            Successfully created page #<?= htmlspecialchars($_GET['created']) ?>
        </div>
    <?php endif; ?>

    <!-- Add links at bottom too if wanted -->
    <div class="admin-links" style="margin-top: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <a href="create-page.php">＋ Create New Page</a>
    </div>

    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?= $sort === 'id' ? $new_order : 'asc' ?>">ID</a></th>
                <th><a href="?sort=title&order=<?= $sort === 'title' ? $new_order : 'asc' ?>">Title</a></th>
                <th><a href="?sort=date&order=<?= $sort === 'date' ? $new_order : 'asc' ?>">Date</a></th>
                <th>Categories</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td>
                        <a href="update-page.php?id=<?= $page['id'] ?>">
                            <?= htmlspecialchars($page['id']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="update-page.php?id=<?= $page['id'] ?>">
                            <?= htmlspecialchars($page['title']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($page['date']) ?></td>
                    <td><?= htmlspecialchars($page['category']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Add links at bottom too if wanted -->
    <div class="admin-links" style="margin-top: 20px;">
        <a href="dashboard.php">← Back to Dashboard</a>
        <a href="create-page.php">＋ Create New Page</a>
    </div>
</body>

</html>