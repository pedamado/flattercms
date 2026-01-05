<?php
function display_category_pages($category_name)
{
    $pages = get_pages_by_category_name($category_name);

    if (empty($pages)) return;
?>
    <section class="category-section">
        <h2><?php echo htmlspecialchars(ucfirst($category_name)) ?> Articles</h2>

        <?php foreach ($pages as $page): ?>
            <article class="page-card">
                <?php if (!empty($page['feature_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($page['feature_image']) ?>"
                        alt="<?php echo htmlspecialchars($page['title']) ?>"
                        class="card-image">
                <?php endif; ?>

                <div class="card-content">
                    <h3>
                        <a href="single.php?id=<?php echo $page['id'] ?>">
                            <?php echo htmlspecialchars($page['title']) ?>
                        </a>
                    </h3>

                    <div class="meta">
                        <time><?php echo date('M j, Y', strtotime($page['date'])) ?></time>
                        <div class="categories">
                            <?php
                            $categories = explode(',', $page['category']);
                            foreach ($categories as $cat):
                                $cat = trim($cat);
                            ?>
                                <span class="category-tag <?php echo ($cat === $category_name) ? 'active-category' : '' ?>">
                                    <?php echo htmlspecialchars($cat) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if (!empty($page['excerpt'])): ?>
                        <p class="excerpt"><?php echo htmlspecialchars($page['excerpt']) ?></p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php
}
