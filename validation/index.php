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

        foreach ($validators as $validator) {
            $error_message = null;

            // TODO: Parse $validator to get validator function name and its params

            if (is_callable($validator)) {
                $error_message = call_user_func($validator, $field, $form_data);
            }

            if ($error_message !== null) {
                $form_errors[$field] = $error_message;
            } else {
                unset($form_erros[$field]);
            }
        }
    }

    return $form_errors;
}
