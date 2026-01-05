<!-- Page list snippet (save as snippets/page-list.php) -->
<div class="page-list">
    <?php
    $all_pages = get_all_pages('date', 'DESC');
    if (empty($all_pages)): ?>
        <p class="no-pages">No pages found.</p>
    <?php else: ?>
        <?php foreach ($all_pages as $page): ?>

            <article class="page-summary" data-page-id="<?php echo $page['id']; ?>">


                <?php if ($page['feature_image']): ?>
                    <a href="single.php?id=<?php echo $page['id']; ?>">
                        <img src="<?php echo htmlspecialchars($page['feature_image']); ?>"
                            alt="<?php echo htmlspecialchars($page['title']); ?>"
                            class="page-thumbnail">
                    </a>
                <?php endif; ?>

                <div class="page-content">
                    <h3 class="page-title">
                        <a href="single.php?id=<?php echo $page['id']; ?>">
                            <?php echo htmlspecialchars($page['title']); ?>
                        </a>
                    </h3>

                    <div class="page-meta">
                        <time datetime="<?php echo htmlspecialchars($page['date']); ?>">
                            <?php echo date('F j, Y', strtotime($page['date'])); ?>
                        </time>

                        <?php if (!empty($page['category'])): ?>
                            <div class="page-categories">
                                <?php
                                $categories = explode(',', $page['category']);
                                foreach ($categories as $cat):
                                    $cat = trim($cat);
                                ?>
                                    <span class="category-badge"><?php echo htmlspecialchars($cat); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($page['excerpt'])): ?>
                        <p class="page-excerpt">
                            <?php echo htmlspecialchars($page['excerpt']); ?>
                        </p>
                    <?php endif; ?>

                    <a href="single.php?id=<?php echo $page['id']; ?>" class="read-more">
                        Read more &raquo;
                    </a>
                </div>
            </article>

        <?php endforeach; ?>
    <?php endif; ?>
</div>