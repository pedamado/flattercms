<?php
require_once 'shared-config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CMS Test</title>
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

    <main>

        <section id="categories">

            <!-- Get the PHP Categories Functions   -->
            <?php include 'functions/categories.php'; ?>

            <!-- Get all categories in a ul list -->
            <?php include 'includes/categories-list.php'; ?>

        </section>
        <!-- Get the PHP Page Functions and content  -->
        <?php include 'functions/page.php'; ?>

        <section>
            <article>
                <!-- Get the specific page & its content with ID 1 -->
                <?php $page = get_page_by_id(1); ?>

                <h1>
                    <?php echo $page['title']; ?>
                </h1>

                <p>
                    <?php echo $page['content']; ?>
                </p>
            </article>
        </section>

        <!-- Get all the pages (function) -->
        <?php include 'functions/pages-all.php'; ?>

        <section>
            <h1>Show all pages (last 5)</h1>

            <!-- Show latest 5 pages -->
            <?php
            $recent_pages = array_slice(get_all_pages('date', 'DESC'), 0, 5);
            include 'includes/pages-list-all.php';
            ?>
        </section>

        <!-- Get  the pages by category name (function) -->
        <?php include 'functions/pages-by-category-name.php'; ?>

        <section>
            <h1>Show some pages (by category name: "news")</h1>

            <!-- Show "News" category pages -->
            <?php
            include 'includes/category-pages.php';
            display_category_pages('news');
            ?>
        </section>

        <!-- Get  the pages by category names (plural!) (function) -->
        <?php include 'functions/pages-by-category-names.php'; ?>
        <section>
            <h1>Show some pages (by multiple category names: "design", "lifestyle")</h1>

            <!-- Show "News" category pages -->
            <?php
            include 'includes/multiple-category-pages.php';
            display_multiple_category_pages('design', 'lifestyle');
            ?>
        </section>

    </main>
</body>

</html>