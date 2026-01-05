<?php
// category.php
require_once 'shared-config.php';
require_once 'functions/page.php';
require_once 'functions/pages-all.php';
require_once 'functions/categories.php';
require_once 'functions/pages-by-category-id.php';

// Validate and get category ID
$category_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$category_id || $category_id < 1) {
    http_response_code(400);
    die('Invalid category ID');
}

// Get category data
$category = get_category_by_id($category_id);

if (!$category || !isset($category['name'])) {
    http_response_code(404);
    die('Category not found');
}

// Get pages for this category
$pages = get_pages_by_category_id($category_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category['name']) ?> - Category</title>

</head>

<body>

    <header>
        <i><a href="<?php echo BASE_URL; ?>">Home</a></i>
        <!-- Get the PHP Menu Functions  -->
        <?php include 'functions/menu.php'; ?>
        <nav>
            <!-- Include the menu list -->
            <?php include 'includes/menu-list.php'; ?>
        </nav>
    </header>

    <main id="<?= htmlspecialchars($category['name']) ?>">
        <h1>Category: <?= htmlspecialchars($category['name']) ?></h1>
        <?php if (!empty($pages)): ?>
            <div class="page-list">
                <?php foreach ($pages as $page): ?>
                    <div class="page-item">
                        <h2>
                            <a href="single.php?id=<?= $page['id'] ?>">
                                <?= htmlspecialchars($page['title']) ?>
                            </a>
                        </h2>
                        <div class="meta">
                            <time><?= date('F j, Y', strtotime($page['date'])) ?></time>
                            <div class="categories">
                                Categories:
                                <?php foreach (explode(',', $page['category']) as $cat): ?>
                                    <span class="category-tag">
                                        <?= htmlspecialchars(trim($cat)) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php if (!empty($page['excerpt'])): ?>
                            <p class="excerpt"><?= htmlspecialchars($page['excerpt']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No pages found in this category.</p>
        <?php endif; ?>
    </main>

    <footer>
        <a href="index.php">Back to Home</a>
    </footer>
</body>

</html>