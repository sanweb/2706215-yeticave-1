<?php

declare(strict_types=1);

const VALIDATOR_SEPARATOR = ':';
const VALIDATOR_PARAMS_SEPARATOR = '&';
const VALIDATOR_PARAM_VALUE_SEPARATOR = '=';
//const VALIDATOR_FUNCTION_PREFIX = 'validate_';

// Forms
// TODO: Create enum
const ADD_LOT_FORM_KEY = 'add-lot';

/**
 * Required form fields grouped by form key.
 *
 * @var array<string, string[]>
 */
const VALIDATION_RULES = [
    ADD_LOT_FORM_KEY => [
        'category_id' => ['required', 'exists:target=categories.id'],
        'title'       => ['required', 'string:min=5&max=255'],
        'description' => ['required', 'string:min=30'],
        'start_price' => ['required', 'int:min=1'],
        'bet_step'    => ['required', 'int:min=1'],
        'expire_date' => ['required', 'date:gt=today'],
    ],
];
