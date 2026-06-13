<?php

declare(strict_types=1);

/**
 * Validator functions contract:
 *
 * Each validator receives:
 * - field name;
 * - full form data;
 * - parsed validator parameters;
 * - optional validation context.
 *
 * Each validator returns:
 * - validation error message as string;
 * - null if the field is valid.
 *
 * Empty values:
 * - validate_required() checks that the value is not empty;
 * - other validators should skip empty values and return null.
 *
 * Some validators may not use $params or $context, but keep them
 * in the signature to make all validators callable in the same way.
 */

/**
 * Registered validator aliases.
 *
 * @var list<string>
 */
const REGISTERED_VALIDATORS = [
    'required',
    'int',
    'string',
    'date',
    'email',
    'exists',
    'unique',
];

/**
 * Prefix used to build validator function names.
 */
const VALIDATOR_FUNCTION_PREFIX = 'validate_';

/**
 * Returns validator function by alias.
 *
 * @param string $validator_alias Validator alias.
 *
 * @return callable|null Validator function or null if unavailable.
 */
function get_validator_function(string $validator_alias): ?callable
{
    if (in_array($validator_alias, REGISTERED_VALIDATORS, true)) {
        $validator_func = VALIDATOR_FUNCTION_PREFIX . $validator_alias;
    }

    if (!isset($validator_func) || !is_callable($validator_func)) {
        $validator_func = null;
    }

    return $validator_func ?? null;
}

/**
 * Validates that the field is not empty.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 * @noinspection PhpUnusedParameterInspection
 */
function validate_required(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (
        !isset($data[$field]) ||
        is_empty(is_string($data[$field]) ? trim($data[$field]) : $data[$field])
    ) {
        $message = 'Заполните это поле';
    }

    return $message ?? null;
}

/**
 * Validates an integer field.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 * @noinspection PhpUnusedParameterInspection
 */
function validate_int(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (!isset($data[$field])) {
        $message = null;
    } elseif (filter_var($data[$field], FILTER_VALIDATE_INT) === false) {
        $message = 'Значение должно быть целочисленным числом';
    } elseif (isset($params['min']) && intval($data[$field]) < intval($params['min'])) {
        $message = 'Значение должно быть целочисленным числом больше или равно ' . $params['min'];
    } elseif (isset($params['max']) && intval($data[$field]) > intval($params['max'])) {
        $message = 'Значение должно быть целочисленным числом меньше или равно ' . $params['max'];
    }

    return $message ?? null;
}

/**
 * Validates a string field.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 * @noinspection PhpUnusedParameterInspection
 */
function validate_string(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (!isset($data[$field])) {
        $message = null;
    } elseif (!is_string($data[$field])) {
        $message = 'Значение должно быть строкой';
    } elseif (isset($params['min']) && mb_strlen(trim($data[$field])) < intval($params['min'])) {
        $message = 'Значение должно быть строкой, не короче ' . $params['min'] . ' символов';
    } elseif (isset($params['max']) && mb_strlen(trim($data[$field])) > intval($params['max'])) {
        $message = 'Значение должно быть строкой, не длиннее ' . $params['max'] . ' символов';
    }

    return $message ?? null;
}

/**
 * Validates an email field.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 * @noinspection PhpUnusedParameterInspection
 */
function validate_email(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (!isset($data[$field])) {
        $message = null;
    } elseif (filter_var($data[$field], FILTER_VALIDATE_EMAIL) === false) {
        $message = 'Введите e-mail';
    }

    return $message ?? null;
}

/**
 * Validates a date field.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 * @noinspection PhpUnusedParameterInspection
 */
function validate_date(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (!isset($data[$field])) {
        $message = null;
    } elseif (!is_datetime_valid($data[$field])) {
        $message = 'Некорректная дата';
    } elseif (isset($params['gt'])) {
        $current_date = strtotime($data[$field]);
        $future_date = strtotime($params['gt']);

        if ($current_date <= $future_date) {
            $message = 'Дата должна быть больше чем ' . date('Y-m-d', $future_date);
        }
    }

    return $message ?? null;
}

/**
 * Validates that the field value exists in the database.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 */
function validate_exists(string $field, array $data, array $params = [], array $context = []): ?string
{
    $db_connection = $context['db'] ?? null;
    $target = $params['target'] ?? null;

    if (!isset($data[$field]) || is_empty($data[$field])) {
        $message = null;
    } elseif (!$db_connection instanceof mysqli) {
        $message = 'Ошибка валидации';
    } elseif (!is_string($target) || !is_exists_validator_allowed_target($target)) {
        $message = 'Ошибка валидации';
    } elseif (!is_db_value_exists($db_connection, $target, $data[$field])) {
        $message = 'Недопустимое значение';
    }

    return $message ?? null;
}

/**
 * Validates that the field value is unique in the database.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 * @param array<string, mixed> $context Validation context.
 *
 * @return string|null Error message or null if valid.
 *
 * @noinspection PhpUnused
 */
function validate_unique(string $field, array $data, array $params = [], array $context = []): ?string
{
    $db_connection = $context['db'] ?? null;
    $target = $params['target'] ?? null;

    if (!isset($data[$field]) || is_empty($data[$field])) {
        $message = null;
    } elseif (!$db_connection instanceof mysqli) {
        $message = 'Ошибка валидации';
    } elseif (!is_string($target) || !is_exists_validator_allowed_target($target)) {
        $message = 'Ошибка валидации';
    } elseif (is_db_value_exists($db_connection, $target, $data[$field])) {
        $message = 'Значение уже используется';
    }

    return $message ?? null;
}

/**
 * Checks whether a value is empty for validation purposes.
 *
 * @param mixed $value Value to check.
 *
 * @return bool True if value is empty.
 */
function is_empty(mixed $value): bool
{
    return $value === null || $value === [] || $value === '';
}
