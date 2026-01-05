<?php
// admin/includes/admin-functions.php
function get_next_page_id()
{
    $pages_dir = '/pages/';
    $highest_id = 0;

    if (is_dir($pages_dir)) {
        $files = scandir($pages_dir);
        foreach ($files as $file) {
            if (preg_match('/page\-(\d+)\.json$/', $file, $matches)) {
                $current_id = (int)$matches[1];
                if ($current_id > $highest_id) {
                    $highest_id = $current_id;
                }
            }
        }
    }

    return $highest_id + 1;
}

function sanitize_page_content($content)
{
    // Basic HTML sanitization (expand allowed tags as needed)
    $allowed_tags = '<h1><h2><h3><p><ul><ol><li><blockquote><strong><em><u><s><mark><small><code><a><img><br>';
    return strip_tags($content, $allowed_tags);
}
