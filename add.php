<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

if (!is_auth()) {
    //http_response_code(HttpCodeEnum::FORBIDDEN->value);
    redirect_to('/login.php');
}

$form_data = [];
$form_errors = [];

if (is_post_request()) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        VALIDATION_RULES[CREATE_LOT_FORM_KEY],
        $form_data,
        $form_errors,
        ['db' => $db_connection]
    );

    process_lot_image($form_data, $form_errors);

    if (empty($form_errors)) {
        $data = build_create_lot_form_data($form_data, $user);

        $lot_id = create_lot($db_connection, $data);

        if ($lot_id) {
            redirect_to('/lot.php?id=' . $lot_id);
        }

        // TODO: Add proper error handling if lot creation fails.
    }
}

$main_content = include_template('add-lot.php', [
    'categories'  => $categories,
    'form_name'   => CREATE_LOT_FORM_KEY,
    'form_data'   => $form_data,
    'form_errors' => $form_errors,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Добавление лота',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
    'css_files'      => ['/assets/css/flatpickr.min.css'],
    'js_files'       => ['/assets/js/flatpickr.js', '/assets/js/script.js'],
]);

echo $page_content;
