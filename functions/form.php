<?php

declare(strict_types=1);

const SEARCH_PHRASE_MAX_LENGTH = 255;

/**
 * Processes uploaded lot image file and updates form data or validation errors.
 *
 * @param array<string, mixed> $form_data Submitted form data.
 * @param array<string, string> $form_errors Validation errors indexed by field name.
 *
 * @return void
 */
function process_lot_image(array &$form_data, array &$form_errors): void
{
    // Image file field
    $file_input_name = 'lot_image_file';
    $file_field = 'image_url';

    // Upload image file if exists
    if (!empty($_FILES[$file_input_name])) {
        $saved_file_name = save_uploaded_file($file_input_name);

        if ($saved_file_name) {
            $form_data[$file_field] = $saved_file_name;
            unset($form_errors[$file_input_name]);
        } else {
            $form_data[$file_field] = '';
            $form_errors[$file_input_name] = 'Ошибка при загрузке файла';
        }
    } else {
        $form_errors[$file_input_name] = 'Загрузите файл';
    }
}

/**
 * Builds ordered lot data for inserting a new lot into the database.
 *
 * @param array<string, mixed> $form_data Validated add-lot form data.
 * @param array<string, mixed> $user Current authenticated user data.
 *
 * @return array<int, mixed> Ordered values for prepared statement binding.
 */
function build_create_lot_form_data(array $form_data, array $user): array
{
    return [
        (int) ($user['id'] ?? 0),
        (int) $form_data['category_id'],
        $form_data['title'],
        $form_data['description'],
        $form_data['image_url'],
        (int) $form_data['start_price'],
        (int) $form_data['bet_step'],
        $form_data['expire_date'],
    ];
}

/**
 * Builds ordered bet data for inserting a new bet into the database.
 *
 * @param array<string, mixed> $form_data Validated bet form data.
 * @param array<string, mixed> $user Current authenticated user data.
 * @param array<string, mixed> $lot Current lot data.
 *
 * @return array<int, mixed> Ordered values for prepared statement binding.
 */
function build_create_bet_form_data(array $form_data, array $user, array $lot): array
{
    return [
        (int) ($user['id'] ?? 0),
        (int) ($lot['id'] ?? 0),
        (int) $form_data['amount'],
    ];
}

/**
 * Builds ordered user data for inserting a new user into the database.
 *
 * @param array<string, mixed> $form_data Validated registration form data.
 *
 * @return array<int, mixed> Ordered values for prepared statement binding.
 */
function build_create_user_form_data(array $form_data): array
{
    $password_hash = password_hash($form_data['password'], PASSWORD_DEFAULT);

    return [
        $form_data['email'],
        $form_data['name'],
        $password_hash,
        $form_data['contact_info'],
    ];
}

/**
 * Builds an HTML field ID based on form and field names.
 *
 * @param string $form_name Form name.
 * @param string $field_name Field name.
 *
 * @return string Field ID.
 */
function build_form_field_id(string $form_name, string $field_name): string
{
    return $form_name . '-' . $field_name;
}

/**
 * Checks whether the bet form is available for the current user.
 *
 * The bet form is unavailable for guests, the lot author,
 * and the user who has already placed the current highest bet.
 *
 * @param array<string, mixed> $lot Lot data.
 * @param int|null $user_id Current user ID, or null for guest.
 *
 * @return bool True if the user can place a bet, false otherwise.
 */
function is_bet_form_available(array $lot, ?int $user_id): bool
{
    $lot_author_id = isset($lot['author_id']) ? (int) $lot['author_id'] : null;
    $max_bet_user_id = isset($lot['max_bet_user_id']) ? (int) $lot['max_bet_user_id'] : null;

    return empty($lot['is_expired'])
        && isset($user_id, $lot_author_id)
        && $lot_author_id !== $user_id
        && $max_bet_user_id !== $user_id;
}

/**
 * Normalizes a search phrase.
 *
 * @param mixed $value
 *
 * @return string
 */
function normalize_search_phrase(mixed $value): string
{
    $value = is_string($value) ? trim($value) : '';

    if (mb_strlen($value) > SEARCH_PHRASE_MAX_LENGTH) {
        $value = mb_substr($value, 0, SEARCH_PHRASE_MAX_LENGTH);
    }

    return $value;
}

/**
 * Normalizes a value to a positive integer.
 *
 * @param mixed $value
 * @param int $default
 *
 * @return int Positive integer, or default value.
 */
function normalize_positive_int(mixed $value, int $default = 1): int
{
    $result = $default;

    if (is_int($value) || is_string($value)) {
        $filtered_value = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1,
            ],
        ]);

        if ($filtered_value !== false) {
            $result = $filtered_value;
        }
    }

    return $result;
}
