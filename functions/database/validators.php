<?php

declare(strict_types=1);

/**
 * Allowed database targets for existence checks.
 *
 * Format:
 * target key => [table name, column name, mysqli bind type]
 *
 * @var array<string, array{0: string, 1: string, 2: 'i'|'d'|'s'}>
 */
const EXISTS_VALIDATOR_ALLOWED_TARGETS = [
    'categories.id' => ['categories', 'id', 'i'],
    'users.email' => ['users', 'email', 's'],
];

/**
 * Checks whether a value exists in an allowed database target.
 *
 * Supports only simple single-column checks.
 *
 * @param mysqli $connection
 * @param string $target_key
 * @param string $value
 * @return bool True if the value exists, false otherwise.
 */
function is_db_value_exists(mysqli $connection, string $target_key, string $value): bool
{
    $value_exists = false;
    $target_config = get_exists_validator_allowed_target($target_key);

    if ($target_config !== null) {
        [$table, $column, $mysqli_bind_type] = $target_config;

        $value = cast_value_to_mysqli_bind_type($value, $mysqli_bind_type);

        $sql = "SELECT 1 FROM `$table` WHERE `$column` = ? LIMIT 1";
        $result = get_stmt_result($connection, $sql, $mysqli_bind_type, [$value]);

        $value_exists = (bool) mysqli_fetch_assoc($result);
    }

    return $value_exists;
}

/**
 * Checks whether the target key is allowed for the exists validator.
 *
 * @param string $target_key
 * @return bool True if the target key is allowed, false otherwise.
 */
function is_exists_validator_allowed_target(string $target_key): bool
{
    return isset(EXISTS_VALIDATOR_ALLOWED_TARGETS[$target_key]);
}

/**
 * Returns allowed target config for the exists validator.
 *
 * Returned array format:
 * [table name, column name, mysqli bind type]
 *
 * @param string $target_key
 * @return array{0: string, 1: string, 2: string}|null Target config or null if target key is not allowed.
 */
function get_exists_validator_allowed_target(string $target_key): ?array
{
    return EXISTS_VALIDATOR_ALLOWED_TARGETS[$target_key] ?? null;
}

/**
 * Casts a string value to the PHP type expected by mysqli_stmt::bind_param().
 *
 * Intentionally supports only scalar bind types used by form/database validation:
 * - i: integer
 * - d: double/float
 * - s: string
 *
 * The "b" blob type is not supported here because it requires separate handling.
 *
 * @param string $value
 * @param string $mysqli_bind_type Mysqli bind type: i, d or s.
 * @return int|float|string
 */
function cast_value_to_mysqli_bind_type(string $value, string $mysqli_bind_type): int|float|string
{
    return match ($mysqli_bind_type) {
        'i' => (int) $value,
        'd' => (float) $value,
        's' => $value,
        default => $value, // fallback to string
    };
}
