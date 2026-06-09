<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

if (!is_auth()) {
    redirect_to('/login.php');
}

$user_bets = get_lots_by_user_id($db_connection, get_user_id());

$main_content = include_template('my-bets.php', [
    'categories' => $categories,
    'user_bets'  => $user_bets,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Мои ставки',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
