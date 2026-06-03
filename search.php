<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$search_phrase = (string) ($_GET['search'] ? trim($_GET['search']) : '');
$current_page = (int) ($_GET['page'] ?? 1);

$search_results = $search_phrase !== '' ? get_lots_by_phrase($db_connection, $search_phrase) : null;

$pagination = !empty($search_results) ? build_pagination(
    '/search.php',
    ['search' => $search_phrase],
    $search_results ? count($search_results) : 0,
    $current_page
) : [];

$page_title = 'Результаты поиска';
$main_template = 'search.php';

$main_content = include_template($main_template, compact(
    'categories',
    'search_phrase',
    'search_results',
    'pagination',
    'current_page'
));

$page_content = include_template('layout/main.php', [
    'page_title'     => $page_title,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'search_phrase'  => $search_phrase,
    'main_classname' => '',
]);

echo $page_content;
