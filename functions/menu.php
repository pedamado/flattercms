<?php

// Add to functions.php
function get_all_menus()
{
    $menus_dir = __DIR__ . '/../menus/';
    $menus = [];

    // Check if directory exists
    if (!is_dir($menus_dir)) {
        return $menus;
    }

    // Scan directory for JSON files
    $files = scandir($menus_dir);
    foreach ($files as $file) {
        // Only process menu-*.json files
        if (preg_match('/^menu\-(\d+)\.json$/', $file, $matches)) {
            $file_path = $menus_dir . $file;
            $json_content = file_get_contents($file_path);
            $menu_data = json_decode($json_content, true);

            // Validate menu structure
            if ($menu_data && isset($menu_data['id'], $menu_data['label'], $menu_data['link_type'], $menu_data['link_value'])) {
                $menus[] = $menu_data;
            }
        }
    }

    // // Sort menus by ID
    // usort($menus, function($a, $b) {
    //     return $a['id'] <=> $b['id'];
    // });

    return $menus;
}

$menus = get_all_menus();
