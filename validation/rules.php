<?php

declare(strict_types=1);

/**
 * Required form fields grouped by form key.
 *
 * @var array<string, string[]>
 */
const VALIDATION_RULES = [
    CREATE_LOT_FORM_KEY => [
        'category_id' => ['required', 'exists:target=categories.id'],
        'title'       => ['required', 'string:min=5&max=255'],
        'description' => ['required', 'string:min=30'],
        'start_price' => ['required', 'int:min=1'],
        'bet_step'    => ['required', 'int:min=1'],
        'expire_date' => ['required', 'date:gt=today'],
    ],
    CREATE_USER_FORM_KEY => [
        'email'        => ['required', 'email', 'unique:target=users.email'],
        'name'         => ['required', 'string:min=3&max=128'],
        'password'     => ['required', 'string:min=8&max=128'],
        'contact_info' => ['required', 'string:min=30'],
    ],
    LOGIN_USER_FORM_KEY => [
        'email'        => ['required', 'email', 'login_email:target=users.email'],
        'password'     => ['required', 'string:max=128'],
    ],
];
