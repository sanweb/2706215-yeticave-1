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

// Available validators
const REGISTERED_VALIDATORS = [
    'required',
    'int',
    'string',
    'date',
    'email',
    'exists',
    'unique',
];

// Validator function prefix
const VALIDATOR_FUNCTION_PREFIX = 'validate_';

/**
 * @param string $validator_alias
 *
 * @return callable|null Validator function if it is defined or null otherwise.
 */
function get_validator_function(string $validator_alias): ?callable
{
    if (in_array($validator_alias, REGISTERED_VALIDATORS, true)) {
        $validator_func = VALIDATOR_FUNCTION_PREFIX . $validator_alias;
    } /* else {
        exit('Попытка применения незарегистрированного валидатора');
    } */

    if (!isset($validator_func) || !is_callable($validator_func)) {
        $validator_func = null;
        //exit('Ошибка получения функции валидации');
    }

    // TODO: Add proper error handling if validator function is not defined.

    return $validator_func ?? null;
}

/**
 * Validates that field value is not empty.
 *
 * @param string $field Field name.
 * @param array<string, mixed> $data Form data.
 * @param array<string, string> $params Validator parameters.
 *
 * @return string|null
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
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
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
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
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
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
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
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
 */
function validate_date(string $field, array $data, array $params = [], array $context = []): ?string
{
    if (!isset($data[$field])) {
        $message = null;
    } elseif (!is_datetime_valid($data[$field], DATE_FORMAT)) {
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
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
 */
function validate_exists(string $field, array $data, array $params = [], array $context = []): ?string
{
    $db_connection = $context['db'] ?? null;

    if (!isset($data[$field]) || is_empty($data[$field])) {
        $message = null;
    } elseif (!$db_connection instanceof mysqli) {
        $message = 'Ошибка валидации';
    } elseif (!isset($params['target']) || !is_exists_validator_allowed_target((string) $params['target'])) {
        $message = 'Ошибка валидации';
    } elseif (!is_db_value_exists($db_connection, $params['target'], $data[$field])) {
        $message = 'Недопустимое значение';
    }

    return $message ?? null;
}

/**
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 * @param array $context
 *
 * @return string|null
 */
function validate_unique(string $field, array $data, array $params = [], array $context = []): ?string
{
    $db_connection = $context['db'] ?? null;

    if (!isset($data[$field]) || is_empty($data[$field])) {
        $message = null;
    } elseif (!$db_connection instanceof mysqli) {
        $message = 'Ошибка валидации';
    } elseif (!isset($params['target']) || !is_exists_validator_allowed_target((string) $params['target'])) {
        $message = 'Ошибка валидации';
    } elseif (is_db_value_exists($db_connection, $params['target'], $data[$field])) {
        $message = 'Значение уже используется';
    }

    return $message ?? null;
}

/**
 * Helper
 *
 * @param mixed $value
 *
 * @return bool
 */
function is_empty($value): bool
{
    return $value === null || $value === [] || $value === '';
}
