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
        'category_id',
        'title',
        'description',
        'start_price',
        'bet_step',
        'expire_date',
    ],
];
