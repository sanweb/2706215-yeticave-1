<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$search_phrase = normalize_search_phrase(get_query_param('search', ''));
$current_page = normalize_positive_int(get_query_param('page', 1));

$total_lots = $search_phrase !== ''
    ? get_total_lots_by_phrase($db_connection, $search_phrase)
    : 0;

$search_results = $total_lots > 0
    ? get_lots_by_phrase($db_connection, $search_phrase, LOTS_PER_PAGE, $current_page)
    : [];

$pagination = $total_lots > LOTS_PER_PAGE
    ? build_pagination(
        '/search.php',
        ['search' => $search_phrase],
        $total_lots,
        LOTS_PER_PAGE,
        $current_page
    ) : [];

$main_content = include_template('search.php', compact(
    'categories',
    'search_phrase',
    'search_results',
    'pagination',
    'current_page'
));

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Результаты поиска',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'search_phrase'  => $search_phrase,
    'main_classname' => '',
]);

echo $page_content;
