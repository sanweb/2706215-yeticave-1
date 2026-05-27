<?php

declare(strict_types=1);

// TODO: Add prefix for standard validator functions
const VALIDATOR_MAP = [
    'required' => 'validate_required',
    'int' => 'validate_int',
    'string' => 'validate_string',
    'date' => 'validate_date',
];

// TODO: Add other validators.

/**
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 *
 * @return string|null
 */
function validate_required(string $field, array $data, array $params = []): string|null
{
    $message = null;

    if (
        !isset($data[$field]) ||
        is_empty(is_string($data[$field]) ? trim($data[$field]) : $data[$field])
    ) {
        $message = 'Заполните это поле';
    }

    return $message;
}

/**
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 *
 * @return string|null
 */
function validate_int(string $field, array $data, array $params = []): string|null
{
    $message = null;
    //$integer_pattern = '/^[+-]?\d+$/';
    //!preg_match($integer_pattern, $data[$field])

    if (!isset($data[$field]) || filter_var($data[$field], FILTER_VALIDATE_INT) === false) {
        $message = 'Значение должно быть целочисленным числом';
    } elseif (isset($params['min']) && intval($data[$field]) < intval($params['min'])) {
        $message = 'Значение должно быть целочисленным числом больше или равно ' . $params['min'];
    } elseif (isset($params['max']) && intval($data[$field]) > intval($params['max'])) {
        $message = 'Значение должно быть целочисленным числом меньше или равно ' . $params['max'];
    }

    return $message;
}

/**
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 *
 * @return string|null
 */
function validate_string(string $field, array $data, array $params = []): string|null
{
    $message = null;

    if (!isset($data[$field]) || !is_string($data[$field])) {
        $message = 'Значение должно быть строкой';
    } elseif (isset($params['min']) && mb_strlen(trim($data[$field])) < intval($params['min'])) {
        $message = 'Значение должно быть строкой, не короче ' . $params['min'] . ' символов';
    } elseif (isset($params['max']) && mb_strlen(trim($data[$field])) > intval($params['max'])) {
        $message = 'Значение должно быть строкой, не длиннее ' . $params['max'];
    }

    return $message;
}

/**
 * Validator
 *
 * @param string $field
 * @param array $data
 * @param array $params
 *
 * @return string|null
 */
function validate_date(string $field, array $data, array $params = []): string|null
{
    $message = null;

    if (!isset($data[$field]) || !is_date_valid($data[$field])) {
        $message = 'Некорректная дата';
    } elseif (isset($params['gt'])) {
        $current_date = strtotime($data[$field]);
        $future_date = strtotime($params['gt']);

        if ($current_date <= $future_date) {
            $message = 'Дата должна быть больше чем ' . date('Y-m-d', $future_date);
        }
    }

    return $message;
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
