<ul>

    <?php
    foreach ($menus as $menu):
        // Generate proper link based on type
        $link = ($menu['link_type'] === 'page')
            ? "single.php?id={$menu['link_value']}"
            : "category.php?id={$menu['link_value']}";
    ?>

        <li>
            <a href="<?php echo htmlspecialchars($link); ?>"
                class="menu-item-<?php echo $menu['id']; ?>">
                <?php echo htmlspecialchars($menu['label']); ?>
            </a>

        <?php endforeach; ?>
        </li>

</ul>