<?php
// Add to includes/functions.php
function get_all_pages($sort_by = 'date', $order = 'DESC')
{
    $pages_dir = __DIR__ . '/../pages/';
    $pages = [];

    if (!is_dir($pages_dir)) {
        return $pages;
    }

    $files = scandir($pages_dir);
    foreach ($files as $file) {
        if (preg_match('/^page\-(\d+)\.json$/', $file, $matches)) {
            $page_data = get_page_by_id($matches[1]);
            if ($page_data) {
                $pages[] = $page_data;
            }
        }
    }

    // Sorting logic
    usort($pages, function ($a, $b) use ($sort_by, $order) {
        $compare = 0;

        switch ($sort_by) {
            case 'date':
                $compare = strtotime($a['date']) <=> strtotime($b['date']);
                break;
            case 'title':
                $compare = strcmp($a['title'], $b['title']);
                break;
            case 'id':
            default:
                $compare = $a['id'] <=> $b['id'];
        }

        return ($order === 'DESC') ? -$compare : $compare;
    });

    return $pages;
}
