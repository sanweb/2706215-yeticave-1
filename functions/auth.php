<?php

declare(strict_types=1);

const SESSION_USER_KEY = 'user';
const SESSION_USER_DATA_FIELDS = ['id', 'email', 'name', 'updated_at'];

function authenticate_user(array $user, string $password): bool
{
    $is_authenticated = false;

    if (
        !empty($user)
        && !empty($user['password_hash'])
        && password_verify($password, $user['password_hash'])
    ) {
        //session is already active (started from init.php)
        //session_start();

        $session_user_data = build_session_user_data($user);
        $_SESSION[SESSION_USER_KEY] = $session_user_data;
        $is_authenticated = true;
    }

    return $is_authenticated;
}

function is_auth(): bool
{
    return !is_null(get_auth_user());
}

function get_auth_user(): ?array
{
    return $_SESSION[SESSION_USER_KEY] ?? null;
}

function get_user_id(): ?int
{
    return isset($_SESSION[SESSION_USER_KEY]['id'])
        ? (int) $_SESSION[SESSION_USER_KEY]['id']
        : null;
}

function build_session_user_data(array $user): array
{
    return array_filter($user, function (string $key) {
        return in_array($key, SESSION_USER_DATA_FIELDS, true);
    }, ARRAY_FILTER_USE_KEY);
}
