<?php

define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Authentication check
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

// admin/edit-menus.php
$menus_dir = dirname(__DIR__) . '/menus/';
$menus = [];

// Get all menu files
$files = glob($menus_dir . 'menu-*.json');



// Load and parse menus
foreach ($files as $file) {
    $content = file_get_contents($file);
    $menus[] = json_decode($content, true);
}

// Sorting logic
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'asc';

usort($menus, function ($a, $b) use ($sort, $order) {
    $valueA = $a[$sort];
    $valueB = $b[$sort];

    if ($sort === 'id') {
        $comparison = $valueA <=> $valueB;
    } else {
        $comparison = strcasecmp((string)$valueA, (string)$valueB);
    }

    return $order === 'asc' ? $comparison : -$comparison;
});

$new_order = $order === 'asc' ? 'desc' : 'asc';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Menu Entries</title>
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
    <h1>Manage Menu Entries</h1>

    <?php if (!empty($_GET['created'])): ?>
        <div class="success">
            Successfully created menu entry #<?= htmlspecialchars($_GET['created']) ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?= $sort === 'id' ? $new_order : 'asc' ?>">ID</a></th>
                <th><a href="?sort=label&order=<?= $sort === 'label' ? $new_order : 'asc' ?>">Label</a></th>
                <th><a href="?sort=link_type&order=<?= $sort === 'link_type' ? $new_order : 'asc' ?>">Type</a></th>
                <th><a href="?sort=link_value&order=<?= $sort === 'link_value' ? $new_order : 'asc' ?>">Value</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td>
                        <a href="update-menu.php?id=<?= $menu['id'] ?>">
                            <?= htmlspecialchars($menu['id']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="update-menu.php?id=<?= $menu['id'] ?>">
                            <?= htmlspecialchars($menu['label']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($menu['link_type']) ?></td>
                    <td><?= htmlspecialchars($menu['link_value']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="create-menu.php">Create New Menu Entry</a> |
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>