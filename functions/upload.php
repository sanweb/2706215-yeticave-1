<?php

declare(strict_types=1);

/**
 * Saves an uploaded image file and returns its public URL.
 *
 * The file is validated by upload status, size, extension and MIME type.
 * The saved file name is generated from the file hash.
 *
 * @param string $input_name File input name from the HTML form.
 * @return string|false Public file URL on success, false on failure.
 */
function save_uploaded_file(string $input_name): string|false
{
    if (!is_uploaded_file_valid($input_name)) {
        return false;
    }

    $file_name = $_FILES[$input_name]['name'];
    $tmp_name = $_FILES[$input_name]['tmp_name'];

    $extension = get_uploaded_file_extension($file_name);
    $mime_type = get_uploaded_file_mime_type($tmp_name);

    if (!is_uploaded_image_type_allowed($extension, $mime_type)) {
        return false;
    }

    $new_file_name = generate_uploaded_file_name($tmp_name, $extension);

    if ($new_file_name === false) {
        return false;
    }

    $file_path = UPLOADS_DIR . '/' . $new_file_name;
    $file_url = UPLOADS_URL . '/' . $new_file_name;

    $saved = move_uploaded_file($tmp_name, $file_path);

    return $saved ? $file_url : false;
}

/**
 * Checks whether uploaded file exists and has valid upload status and size.
 *
 * @param string $input_name File input name from the HTML form.
 * @return bool True if uploaded file is valid, false otherwise.
 */
function is_uploaded_file_valid(string $input_name): bool
{
    if (
        empty($_FILES[$input_name]) ||
        empty($_FILES[$input_name]['name']) ||
        empty($_FILES[$input_name]['tmp_name']) ||
        $_FILES[$input_name]['error'] !== UPLOAD_ERR_OK
    ) {
        return false;
    }

    $file_size = $_FILES[$input_name]['size'];

    return $file_size > 0 && $file_size <= MAX_UPLOADED_FILE_SIZE;
}

/**
 * Returns uploaded file extension in lowercase.
 *
 * @param string $file_name Original uploaded file name.
 * @return string File extension.
 */
function get_uploaded_file_extension(string $file_name): string
{
    return strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
}

/**
 * Returns uploaded file MIME type.
 *
 * @param string $tmp_name Temporary uploaded file path.
 * @return string|false MIME type on success, false on failure.
 */
function get_uploaded_file_mime_type(string $tmp_name): string|false
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    if ($finfo === false) {
        return false;
    }

    $mime_type = finfo_file($finfo, $tmp_name);

    finfo_close($finfo);

    return $mime_type;
}

/**
 * Checks whether uploaded image extension matches allowed MIME type.
 *
 * @param string $extension File extension.
 * @param string|false $mime_type File MIME type.
 * @return bool True if image type is allowed, false otherwise.
 */
function is_uploaded_image_type_allowed(string $extension, string|false $mime_type): bool
{
    if ($mime_type === false) {
        return false;
    }

    return isset(ALLOWED_IMAGE_TYPES[$extension]) &&
        ALLOWED_IMAGE_TYPES[$extension] === $mime_type;
}

/**
 * Generates uploaded file name from file hash and extension.
 *
 * @param string $tmp_name Temporary uploaded file path.
 * @param string $extension File extension.
 * @return string|false Generated file name on success, false on failure.
 */
function generate_uploaded_file_name(string $tmp_name, string $extension): string|false
{
    $hash = md5_file($tmp_name);

    if ($hash === false) {
        return false;
    }

    return $hash . '.' . $extension;
}
