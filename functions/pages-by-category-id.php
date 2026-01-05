<?php
// Add to includes/functions.php
function get_pages_by_category_id($category_id)
{
    // 1. Get the category name from ID
    $category = get_category_by_id($category_id);

    if (!$category || !isset($category['name'])) {
        return [];
    }

    $target_category = strtolower(trim($category['name']));

    // 2. Get all pages
    $all_pages = get_all_pages();
    $filtered_pages = [];

    foreach ($all_pages as $page) {
        if (empty($page['category'])) continue;

        // 3. Split and normalize page categories
        $page_categories = array_map(
            function ($cat) {
                return strtolower(trim($cat));
            },
            explode(',', $page['category'])
        );

        // 4. Check for match
        if (in_array($target_category, $page_categories)) {
            $filtered_pages[] = $page;
        }
    }

    // 5. Sort by date (newest first)
    usort($filtered_pages, function ($a, $b) {
        return strtotime($b['date']) <=> strtotime($a['date']);
    });

    return $filtered_pages;
}
