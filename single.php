<?php
// single.php
require_once 'shared-config.php';
require_once 'functions/page.php';

// Get requested page ID
$page_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Validate page ID
if (!$page_id) {
    http_response_code(400);
    die('Invalid page ID');
}

// Get page data
$page = get_page_by_id($page_id);

// Handle missing pages
if (!$page) {
    http_response_code(404);
    die('Page not found');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
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

    <main id="page-<?php echo $page_id; ?>">
        <?php if ($page['feature_image']): ?>
            <img src="uploads/<?php echo htmlspecialchars($page['feature_image']); ?>"
                alt="<?php echo htmlspecialchars($page['title']); ?>"
                class="feature-image">
        <?php endif; ?>

        <h1>
            <?php echo htmlspecialchars($page['title']); ?>
        </h1>

        <div class="meta">
            <time datetime="<?php echo htmlspecialchars($page['date']); ?>">
                <?php echo date('F j, Y', strtotime($page['date'])); ?>
            </time>

            <?php if ($page['category']): ?>
                <div class="categories">
                    <?php
                    $categories = explode(',', $page['category']);
                    foreach ($categories as $cat):
                        $cat = trim($cat);
                    ?>
                        <span class="category-tag"><?php echo htmlspecialchars($cat); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="content">
            <?php
            // Output raw HTML content (ensure this is sanitized in admin interface)
            // echo $page['content']; 

            // To this (remove htmlspecialchars for content only):
            echo htmlspecialchars_decode($page['content']);

            ?>
        </div>
    </main>

    <footer>
        <a href="index.php">Back to Home</a>
    </footer>
</body>

</html>