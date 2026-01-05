<?php


define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/edit-categories.php
$categories_dir = dirname(__DIR__) . '/categories/';
$categories = [];

// Get all category files
$files = glob($categories_dir . 'category-*.json');

// Load and parse categories
foreach ($files as $file) {
    $content = file_get_contents($file);
    $categories[] = json_decode($content, true);
}

// Sorting logic
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

usort($categories, function ($a, $b) use ($sort, $order) {
    $valueA = $a[$sort];
    $valueB = $b[$sort];

    $comparison = $sort === 'id'
        ? $valueA <=> $valueB
        : strcasecmp($valueA, $valueB);

    return $order === 'asc' ? $comparison : -$comparison;
});

$new_order = $order === 'asc' ? 'desc' : 'asc';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Categories</title>
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

        .success {
            color: green;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <h1>Manage Categories</h1>

    <?php if (!empty($_GET['created'])): ?>
        <div class="success">
            Successfully created category #<?= htmlspecialchars($_GET['created']) ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?= $sort === 'id' ? $new_order : 'asc' ?>">ID</a></th>
                <th><a href="?sort=name&order=<?= $sort === 'name' ? $new_order : 'asc' ?>">Name</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>
                        <a href="update-category.php?id=<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['id']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="update-category.php?id=<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="create-category.php">Create New Category</a>
    </div>
</body>

</html>