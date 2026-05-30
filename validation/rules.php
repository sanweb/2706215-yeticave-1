<?php

declare(strict_types=1);

const VALIDATOR_SEPARATOR = ':';
const VALIDATOR_PARAMS_SEPARATOR = '&';
const VALIDATOR_PARAM_VALUE_SEPARATOR = '=';
//const VALIDATOR_FUNCTION_PREFIX = 'validate_';

// Forms
// TODO: Create enum?
const CREATE_LOT_FORM_KEY = 'create-lot';
const CREATE_USER_FORM_KEY = 'create-user';

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
];
