<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$category_id = (int) get_query_param('category_id', 0);
$current_page = normalize_positive_int(get_query_param('page', 1));

// Get current category from available categories that already selected instead of making new db query
$categories_by_id = array_column($categories, null, 'id');
$category = $category_id > 0 && isset($categories_by_id[$category_id]) ? $categories_by_id[$category_id] : null;

if ($category === null) {
    render_page_404($categories, $user);
    exit;
}

$total_lots = get_total_lots_by_category_id($db_connection, $category_id);

$lots = $total_lots > 0
    ? get_lots_by_category_id($db_connection, $category_id, LOTS_PER_PAGE, $current_page)
    : [];

$pagination = $total_lots > LOTS_PER_PAGE
    ? build_pagination(
        '/all-lots.php',
        ['category_id' => $category_id],
        $total_lots,
        LOTS_PER_PAGE,
        $current_page
    ) : [];

$main_content = include_template('all-lots.php', [
    'categories'          => $categories,
    'category'            => $category,
    'current_category_id' => $category_id,
    'lots'                => $lots,
    'pagination'          => $pagination,
    'current_page'        => $current_page,
]);

$page_content = include_template('layout/main.php', [
    'page_title'          => 'Все лоты в категории ' . ($category['name'] ?? '') ,
    'user'                => $user,
    'categories'          => $categories,
    'current_category_id' => $category_id,
    'main_content'        => $main_content,
    'main_classname'      => '',
]);

echo $page_content;
