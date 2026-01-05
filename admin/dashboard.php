<?php
// admin/dashboard.php
define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Redirect if not authenticated
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CMS Dashboard</title>
</head>

<body>
    <header>
        <h1>CMS Dashboard</h1>
        <p>Logged in as: <?php echo ADMIN_USERNAME; ?></p>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section class="dashboard-section">
            <h2>Manage Pages</h2>
            <ul>
                <li><a href="edit-pages.php">List/Edit Pages</a></li>
                <li><a href="create-page.php">Add/Create Page</a></li>
            </ul>
        </section>

        <section class="dashboard-section">
            <h2>Manage Categories</h2>
            <ul>
                <li><a href="edit-categories.php">List/Edit Categories</a></li>
                <li><a href="create-category.php">Add/Create Category</a></li>
            </ul>
        </section>

        <section class="dashboard-section">
            <h2>Manage Menu</h2>
            <ul>
                <li><a href="edit-menu.php">List/Edit Menu Entries</a></li>
                <li><a href="create-menu.php">Add/Create Menu Entry</a></li>
            </ul>
        </section>

        <section class="dashboard-section">
            <h2>Manage Media</h2>
            <ul>
                <li><a href="media-library.php">List/Edit Media</a></li>
                <li><a href="upload-media.php">Add/Upload Media</a></li>
            </ul>
        </section>
    </main>

    <footer>

    </footer>
</body>

</html>