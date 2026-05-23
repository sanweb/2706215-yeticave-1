<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var bool $is_auth */
/** @var array $user */
/** @var array $categories */

$form_data = [];
$form_errors = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        FORM_FIELDS[ADD_LOT_FORM_KEY],
        $form_data,
        $form_errors
    );

    process_image_file($form_data, $form_errors);

    if (empty($form_errors)) {
        $data = build_add_lot_form_data($form_data, $user);

        $added_lot_id = add_lot($db_connection, $data);

        if ($added_lot_id) {
            redirect('/lot.php?id=' . $added_lot_id);
        }

        // TODO: Add proper error handling if lot creation fails.
    }
}

$main_content = include_template('add-lot.php', [
    'categories'  => $categories,
    'form_data'   => $form_data,
    'form_errors' => $form_errors,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Добавление лота',
    'is_auth'        => $is_auth,
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
    'css_files'      => ['/assets/css/flatpickr.min.css'],
    'js_files'       => ['/assets/js/flatpickr.js', '/assets/js/script.js'],
]);

echo $page_content;
