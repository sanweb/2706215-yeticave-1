<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('docker')
    ->exclude('vendor');

return new PhpCsFixer\Config()
    ->setRules([
        '@PSR12'                 => true,
        'binary_operator_spaces' => [
            'default'   => 'single_space',
            'operators' => [
                '=>' => 'align_single_space_minimal',
            ],
        ],
        'new_with_braces' => [
            'anonymous_class' => false,
        ],
        'braces_position' => [
            'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
        ],
    ])
    ->setFinder($finder);
