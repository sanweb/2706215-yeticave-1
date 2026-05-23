<?php

declare(strict_types=1);

// TODO: Add other validators.

/**
 * Validator
 *
 * @param string $field
 * @param array $form_data
 *
 * @return string|null
 */
function validateRequired(string $field, array $form_data): string|null
{
    $message = 'Заполните это поле';

    return (!isset($form_data[$field]) || is_empty(is_string($form_data[$field]) ? trim($form_data[$field]) : $form_data[$field])) ? $message : null;
}

/**
 * Validator
 *
 * @param string $field
 * @param array $form_data
 *
 * @return string|null
 */
function validateDate(string $field, array $form_data): string|null
{
    $message = 'Некорректная дата';

    return (!isset($form_data[$field]) || !is_date_valid($form_data[$field])) ? $message : null;
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