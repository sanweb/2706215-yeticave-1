<?php

declare(strict_types=1);

const VALIDATOR_SEPARATOR = ':';
const VALIDATOR_PARAMS_SEPARATOR = '&';
const VALIDATOR_PARAM_VALUE_SEPARATOR = '=';

/**
 * Validates required form fields and returns validation errors.
 *
 * @param array<string, string[]> $rules Required field rules (field => validators).
 * @param array<string, mixed> $form_data Submitted form data.
 * @param array<string, string> $form_errors Existing validation errors indexed by field name.
 *
 * @return array<string, string> Updated validation errors indexed by field name.
 */
function validate_form_data(array $rules = [], array $form_data = [], array $form_errors = [], $context = []): array
{
    foreach ($rules as $field => $validators) {
        unset($form_errors[$field]);

        foreach ($validators as $validator_string) {
            [$validator_func, $params] = parse_validator($validator_string);

            if (!is_callable($validator_func)) {
                $form_errors[$field] = 'Ошибка валидации';
                break;
            }

            $error_message = $validator_func($field, $form_data, $params, $context);

            if ($error_message !== null) {
                $form_errors[$field] = $error_message;
                break;
            }
        }
    }

    return $form_errors;
}

/**
 * @param string $validator_string
 *
 * @return array
 */
function parse_validator(string $validator_string): array
{
    $validator_alias = null;
    $validator_func = null;
    $params = [];

    if (!str_contains($validator_string, VALIDATOR_SEPARATOR)) {
        $validator_alias = $validator_string;
    } else {
        [$validator_alias, $params_string] = explode(VALIDATOR_SEPARATOR, $validator_string, 2);

        if (!empty($params_string)) {
            $params = parse_validator_params($params_string);
        }
    }

    $validator_func = get_validator_function($validator_alias);
    /*
    if (!is_callable($validator_func)) {
        // TODO: Add proper error handling if validator function is not defined.
    }
    */
    return [$validator_func, $params];
}

/**
 * @param string $params_string
 *
 * @return array
 */
function parse_validator_params(string $params_string): array
{
    $params = [];

    foreach (explode(VALIDATOR_PARAMS_SEPARATOR, $params_string) as $pair) {
        if ($pair === '') {
            continue;
        }

        [$name, $value] = array_pad(explode(VALIDATOR_PARAM_VALUE_SEPARATOR, $pair, 2), 2, '');

        if ($name === '') {
            continue;
        }

        $params[$name] = $value;
    }

    return $params;
}
