<?php
function get_category_by_id($id)
{
    $file = __DIR__ . "/../categories/category-{$id}.json";
    return file_exists($file) ?
        json_decode(file_get_contents($file), true) :
        null;
}
