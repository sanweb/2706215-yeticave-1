<?php

declare(strict_types=1);

/**
 * Session key used to store authenticated user data.
 */
const SESSION_USER_KEY = 'user';

/**
 * User fields allowed to be stored in the session.
 *
 * Important: do not store password_hash or other sensitive/internal fields
 * in the session.
 */
const SESSION_USER_DATA_FIELDS = ['id', 'email', 'name', 'updated_at'];

/**
 * Authenticates user by plain password and stores safe user data in session.
 *
 * Expects the user array to contain a password_hash field.
 * Only fields listed in SESSION_USER_DATA_FIELDS are saved to the session.
 *
 * @param array $user User data from database.
 * @param string $password Plain password from the login form.
 *
 * @return bool True if authentication succeeded, false otherwise.
 */
function authenticate_user(array $user, string $password): bool
{
    $is_authenticated = false;

    if (
        !empty($user)
        && !empty($user['password_hash'])
        && password_verify($password, $user['password_hash'])
    ) {
        $session_user_data = build_session_user_data($user);
        $_SESSION[SESSION_USER_KEY] = $session_user_data;
        $is_authenticated = true;
    }

    return $is_authenticated;
}

/**
 * Checks whether the current user is authenticated.
 *
 * @return bool True if authenticated user data exists in session, false otherwise.
 */
function is_auth(): bool
{
    return get_auth_user() !== null;
}

/**
 * Returns authenticated user data from session.
 *
 * @return array<string, mixed>|null Authenticated user data, or null if user is not authenticated.
 */
function get_auth_user(): ?array
{
    return $_SESSION[SESSION_USER_KEY] ?? null;
}

/**
 * Returns authenticated user ID from session.
 *
 * @return int|null User ID, or null if user is not authenticated.
 */
function get_user_id(): ?int
{
    return isset($_SESSION[SESSION_USER_KEY]['id'])
        ? (int) $_SESSION[SESSION_USER_KEY]['id']
        : null;
}

/**
 * Builds safe user data array for storing in session.
 *
 * Keeps only fields listed in SESSION_USER_DATA_FIELDS.
 *
 * @param array<string, mixed> $user Full user data array, usually from database.
 *
 * @return array<string, mixed> User data allowed to be stored in session.
 */
function build_session_user_data(array $user): array
{
    return array_filter($user, function (string $key) {
        return in_array($key, SESSION_USER_DATA_FIELDS, true);
    }, ARRAY_FILTER_USE_KEY);
}
