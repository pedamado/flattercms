<?php
function get_pages_by_category_names(...$category_names)
{
    $all_pages = get_all_pages();
    $target_categories = array_map('strtolower', array_map('trim', $category_names));
    $filtered_pages = [];

    foreach ($all_pages as $page) {
        if (empty($page['category'])) continue;

        $page_categories = array_map(
            fn($c) => strtolower(trim($c)),
            explode(',', $page['category'])
        );

        // Check if ANY target category matches
        if (array_intersect($target_categories, $page_categories)) {
            $filtered_pages[] = $page;
        }
    }

    // Sort by date descending
    usort(
        $filtered_pages,
        fn($a, $b) =>
        strtotime($b['date']) <=> strtotime($a['date'])
    );

    return $filtered_pages;
}
