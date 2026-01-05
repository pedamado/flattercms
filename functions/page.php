<?php
// functions.php - Save this in your includes folder
function get_page_by_id($page_id)
{
    // Define path to pages directory
    $pages_dir = __DIR__ . '/../pages/';

    // Construct filename
    $filename = $pages_dir . 'page-' . $page_id . '.json';

    // Check if file exists
    if (!file_exists($filename)) {
        return null;
    }

    // Read and decode JSON
    $json_content = file_get_contents($filename);
    $page_data = json_decode($json_content, true);

    // Return page data or null if invalid
    return ($page_data && is_array($page_data)) ? $page_data : null;
}

?>