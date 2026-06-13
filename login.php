<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $db_connection */
/** @var array  $user */
/** @var array  $categories */

if (is_auth()) {
    redirect_to('/');
}

$form_data = [];
$form_errors = [];

if (is_post_request()) {
    $form_data = $_POST;

    $form_errors = validate_form_data(
        VALIDATION_RULES[LOGIN_USER_FORM_KEY],
        $form_data,
        $form_errors,
        ['db' => $db_connection]
    );

    if (empty($form_errors)) {
        $user = get_user_by_email($db_connection, $form_data['email'] ?? '');

        if (!empty($user) && authenticate_user($user, $form_data['password'] ?? '')) {
            redirect_to('/');
        } else {
            $login_error = 'Вы ввели неверный email/пароль';
            $form_errors['email'] = $login_error;
            $form_errors['password'] = $login_error;
        }
    }
}

$main_content = include_template('login.php', [
    'categories'  => $categories,
    'form_name'   => LOGIN_USER_FORM_KEY,
    'form_data'   => $form_data,
    'form_errors' => $form_errors,
]);

$page_content = include_template('layout/main.php', [
    'page_title'     => 'Регистрация',
    'user'           => $user,
    'categories'     => $categories,
    'main_content'   => $main_content,
    'main_classname' => '',
]);

echo $page_content;
