<?php

declare(strict_types=1);

/**
 * Validates required form fields and returns validation errors.
 *
 * @param array<string, string[]> $rules Required field rules (field => validators).
 * @param array<string, mixed> $form_data Submitted form data.
 * @param array<string, string> $form_errors Existing validation errors indexed by field name.
 *
 * @return array<string, string> Updated validation errors indexed by field name.
 */
function validate_form_data(array $rules = [], array $form_data = [], array $form_errors = []): array
{
    foreach ($rules as $field => $validators) {
        $error_message = null;

        foreach ($validators as $validator_string) {

            if ($error_message !== null) {
                continue;
            }

            list($validator_func, $params) = parse_validator($validator_string);

            if (is_callable($validator_func)) {
                //$error_message = call_user_func($validator_func, $field, $form_data, $params);
                $error_message = $validator_func($field, $form_data, $params);
                //dd([$field, $validator_func, $params, $error_message]);
            } else {
                // error
                // TODO: Add proper error handling if validator is not a function.
            }
        }

        if ($error_message) {
            $form_errors[$field] = $error_message;
        } else {
            unset($form_errors[$field]);
        }
    }

    //dd($form_errors);

    return $form_errors;
}

function parse_validator(string $validator_string): array
{
    $validator_alias = null;
    $validator_func = null;
    $params = [];

    //echo $validator_string . PHP_EOL;
    if (strpos($validator_string, VALIDATOR_SEPARATOR) === false) {
        $validator_alias = $validator_string;
    } else {
        list($validator_alias, $params_string) = explode(VALIDATOR_SEPARATOR, $validator_string, 2);

        if (!empty($params_string)) {
            parse_str($params_string, $params);
        }
    }

    if (isset(VALIDATOR_MAP[$validator_alias])) {
        $validator_func = VALIDATOR_MAP[$validator_alias];
    } else {
        // error
        // TODO: Add proper error handling if validator function is not defined.
    }
    //dd([$validator_alias, $validator_func, $params]);
    return [$validator_func, $params];
}
