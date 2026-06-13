<?php

declare(strict_types=1);

const VALIDATOR_SEPARATOR = ':';
const VALIDATOR_PARAMS_SEPARATOR = '&';
const VALIDATOR_PARAM_VALUE_SEPARATOR = '=';

/**
 * Validates form data using the given validation rules.
 *
 * Each rule contains a field name and a list of validators.
 * Validator strings may contain additional parameters.
 *
 * Examples:
 * - `required`
 * - `string:min=5&max=255`
 * - `exists:target=categories.id`
 *
 * Validator callback signature:
 * `validator(string $field, array $form_data, array $params, array $context): ?string`
 *
 * @param array<string, list<string>> $rules Validation rules indexed by field name.
 * @param array<string, mixed> $form_data Submitted form data.
 * @param array<string, string> $form_errors Existing validation errors indexed by field name.
 * @param array<string, mixed> $context Additional validation context.
 *
 * @return array<string, string> Validation errors indexed by field name.
 */
function validate_form_data(
    array $rules = [],
    array $form_data = [],
    array $form_errors = [],
    array $context = []
): array {
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
 * Parses a validator string into a validator function and its parameters.
 *
 * Examples:
 * - `required`
 * - `string:min=5&max=255`
 * - `exists:target=categories.id`
 *
 * @param string $validator_string Validator alias with optional parameters.
 *
 * @return array{0: callable|null, 1: array<string, string>} Validator function and parsed parameters.
 */
function parse_validator(string $validator_string): array
{
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

    return [$validator_func, $params];
}

/**
 * Parses validator parameters from a parameter string.
 *
 * Example:
 * `min=5&max=255`
 *
 * Result:
 * [
 *     'min' => '5',
 *     'max' => '255',
 * ]
 *
 * @param string $params_string Validator parameters string.
 *
 * @return array<string, string> Parsed parameters indexed by parameter name.
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
