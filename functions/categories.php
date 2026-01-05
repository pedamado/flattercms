<?php

// Get all categories
function get_all_categories()
{
    $categories_dir = __DIR__ . '/../categories/';
    $categories = [];

    if (!is_dir($categories_dir)) {
        return $categories;
    }

    $files = scandir($categories_dir);
    foreach ($files as $file) {
        if (preg_match('/^category\-(\d+)\.json$/', $file, $matches)) {
            $file_path = $categories_dir . $file;
            $json_content = file_get_contents($file_path);
            $category_data = json_decode($json_content, true);

            if ($category_data && isset($category_data['id'], $category_data['name'])) {
                $categories[] = $category_data;
            }
        }
    }

    // Sort by ID
    usort($categories, function ($a, $b) {
        return $a['id'] <=> $b['id'];
    });

    return $categories;
}
// Get category by ID
function get_category_by_id($category_id)
{
    $filename = __DIR__ . '/../categories/category-' . $category_id . '.json';

    if (!file_exists($filename)) {
        return null;
    }

    $json_content = file_get_contents($filename);
    $category_data = json_decode($json_content, true);

    return ($category_data && isset($category_data['id'], $category_data['name']))
        ? $category_data
        : null;
}
