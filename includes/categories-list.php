<ul>
    <?php foreach (get_all_categories() as $category): ?>
        <li class="category-<?php echo $category['id']; ?>">
            <a href="category.php?id=<?php echo $category['id']; ?>"
                title="View all posts in <?php echo htmlspecialchars($category['name']); ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>