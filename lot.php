<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var bool $is_auth */
/** @var array $user */
/** @var array $categories */

$lot_id = (int) ($_GET['id'] ?? 0);
$lot = $lot_id > 0 ? get_lot_by_id($db_connection, $lot_id) : null;

if ($lot === null) {
    http_response_code(HttpCodeEnum::NOT_FOUND->value);
}

$page_title = $lot ? $lot['title'] : '404 Страница не найдена';
$main_template = $lot ? 'lot.php' : '404.php';
$main_data = $lot ? compact('lot') : [];

$main_content = include_template($main_template, array_merge(['categories' => $categories], $main_data));

$page_content = include_template('layout/main.php', [
    'page_title'     => $page_title,
    'is_auth'        => $is_auth,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
