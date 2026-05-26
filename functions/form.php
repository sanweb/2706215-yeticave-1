<?php

declare(strict_types=1);

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
function build_add_lot_form_data(array $form_data, array $user): array
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
