<?php

declare(strict_types=1);

// Forms
const ADD_LOT_FORM_KEY = 'add-lot';

/**
 * Required form fields grouped by form key.
 *
 * @var array<string, string[]>
 */
const FORM_FIELDS = [
    ADD_LOT_FORM_KEY => [
        'category_id' => ['required', 'int'],
        'title'       => ['required', 'string:min=5&max=255'],
        'description' => ['required', 'string:min=30&max=1000'],
        'start_price' => ['required', 'int:positive'],
        'bet_step'    => ['required', 'int:positive'],
        'expire_date' => ['required', 'date'],
    ],
];
