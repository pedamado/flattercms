<?php
function get_pages_by_category_name($category_name)
{
    $all_pages = get_all_pages();
    $target = strtolower(trim($category_name));
    $filtered = [];

    foreach ($all_pages as $page) {
        if (empty($page['category'])) continue;

        $page_categories = array_map(
            fn($c) => strtolower(trim($c)),
            explode(',', $page['category'])
        );

        if (in_array($target, $page_categories)) {
            $filtered[] = $page;
        }
    }

    // Sort by date descending
    usort(
        $filtered,
        fn($a, $b) =>
        strtotime($b['date']) <=> strtotime($a['date'])
    );

    return $filtered;
}
