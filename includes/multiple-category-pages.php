<?php

function display_multiple_category_pages(...$category_names)
{
    $pages = get_pages_by_category_names(...$category_names);

    if (empty($pages)) return;

    $display_categories = array_map('ucfirst', $category_names);
?>
    <section class="multi-category-section">
        <h2>
            <?php echo implode(' + ', $display_categories) ?> Articles
            <small>(Combined Categories)</small>
        </h2>

        <div class="page-grid">
            <?php foreach ($pages as $page): ?>
                <article class="page-card">
                    <?php if (!empty($page['feature_image'])): ?>
                        <a href="single.php?id=<?= $page['id'] ?>" class="card-image-link">
                            <img src="uploads/<?= htmlspecialchars($page['feature_image']) ?>"
                                alt="<?= htmlspecialchars($page['title']) ?>"
                                class="card-image">
                        </a>
                    <?php endif; ?>

                    <div class="card-content">
                        <h3>
                            <a href="single.php?id=<?= $page['id'] ?>">
                                <?= htmlspecialchars($page['title']) ?>
                            </a>
                        </h3>

                        <div class="meta">
                            <time><?= date('M j, Y', strtotime($page['date'])) ?></time>
                            <div class="category-tags">
                                <?php
                                $page_categories = explode(',', $page['category']);
                                foreach ($page_categories as $cat):
                                    $clean_cat = trim($cat);
                                    $is_target = in_array(strtolower($clean_cat), $category_names);
                                ?>
                                    <span class="tag <?= $is_target ? 'active-tag' : '' ?>">
                                        <?= htmlspecialchars($clean_cat) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if (!empty($page['excerpt'])): ?>
                            <p class="excerpt"><?= htmlspecialchars($page['excerpt']) ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php
}
