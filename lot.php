<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

$lot_id = (int) get_query_param('id', 0);
$lot = $lot_id > 0 ? get_lot_by_id($db_connection, $lot_id) : null;

if ($lot === null) {
    render_page_404($categories, $user);
    exit;
}

// Process bet form
$is_bet_form_available = $lot && is_bet_form_available($lot, get_user_id());

if ($is_bet_form_available && is_post_request()) {
    $form_data = $_POST;
    $form_errors = validate_form_data(
        VALIDATION_RULES[CREATE_BET_FORM_KEY],
        $form_data,
        [],
        ['db' => $db_connection]
    );

    if (empty($form_errors)) {
        if ($form_data['amount'] < $lot['min_bet']) {
            $form_errors['amount'] = 'Сумма ставки меньше минимальной';
        }
    }

    if (empty($form_errors)) {
        $data = build_create_bet_form_data($form_data, $user, $lot);

        if (create_bet($db_connection, $data)) {
            redirect_to('/lot.php?id=' . $lot_id);
        }
    }
}

$bet_history = get_bet_history_by_lot_id($db_connection, $lot_id);
$main_data = compact('lot', 'categories', 'is_bet_form_available', 'bet_history');

if ($is_bet_form_available) {
    $main_data = array_merge($main_data, [
        'form_name'   => CREATE_BET_FORM_KEY,
        'form_data'   => $form_data ?? [],
        'form_errors' => $form_errors ?? [],
    ]);
}

$main_content = include_template('lot.php', $main_data);

$page_content = include_template('layout/main.php', [
    'page_title'     => $lot['title'] ?? '',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
